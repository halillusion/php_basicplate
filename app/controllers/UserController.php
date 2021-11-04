<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\Core;
use app\core\helpers\Session;
use app\core\helpers\Notification;

class UserController extends Core {

	function __construct() {
		
		$this->viewFolder = 'user';

	}

	public function login () {

		$return = [];

		extract(in([
			'password'	=> 'nulled_text',
			'email'		=> 'nulled_text'
		], $_POST));

		if ($password AND $email) {

			// Get user data
			$get = (new Database)
				->table('users')
				->select('id, u_name, f_name, l_name, email, b_date, password, token, role_id, status')
				->grouped(function($q) use ($email) {
					$q->where('u_name', $email)->orWhere('email', $email);
				})
				->notWhere('status', 'deleted')
				->get();

			if ($get) {

				// Verify password
				if (password_verify($password, $get->password)) {

					// Get user role
					$getRole = (new Database)
						->table('user_roles')
						->select('name, view_points, action_points')
						->where('id', $get->role_id)
						->where('status', 'active')
						->get();

					if ($getRole) {

						$get->role = $getRole;

					}
					unset($get->password);

					if ((new Session)->create($get)) {

						$return = [
							'status'	=> 'success',
							'title'		=> 'alert.success',
							'message'	=> 'alert.you_are_logged_in',
							'alert_type'=> 'toast',
							'reload'	=> [base(), 3]
						];

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.session_create_fail',
							'alert_type'=> 'toast'
						];
					}

				} else {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.user_info_incorrect',
						'alert_type'=> 'toast'
					];
				}


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.account_not_found',
					'alert_type'=> 'toast'
				];

			}

		} else {

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

		}

		Response::out($return);

	}

	public function logout() {

		if (isset($_COOKIE[config('app.session')]) !== false) {
			(new Database)
				->table('sessions')
				->where('auth_code', $_COOKIE[config('app.session')])
				->delete();
		}

		session_destroy();

		$return = [
			'status'	=> 'success',
			'title'		=> 'alert.success',
			'message'	=> 'alert.you_are_logged_out',
			'alert_type'=> 'toast',
			'reload'	=> [base(), 3]
		];

		Response::out($return);

	}

	public function register () {

		$return = [];

		extract(in([
			'password'	=> 'nulled_text',
			'email'		=> 'nulled_text',
			'username'	=> 'nulled_text'
		], $_POST));

		if ($password AND $email AND $username) {

			// Get user data
			$get = (new Database)
				->table('users')
				->select('email, u_name')
				->grouped(function($q) use ($email, $username) {
					$q->where('email', $email)->orWhere('u_name', $username);
				})
				->get();

			if (! $get) {

				$token = $this->createToken();
				
				$insert = (new Database)
					->table('users')
					->insert([
						'u_name'	=> $username,
						'email'		=> $email,
						'password'	=> password_hash($password, PASSWORD_DEFAULT),
						'token'		=> $token,
						'role_id'	=> config('settings.default_user_role'),
						'created_at'=> time()
					]);

				if ($insert) {

					(new Notification)::create('register', [
						'u_name'	=> $username,
						'email'		=> $email,
						'token'		=> $token,
						'user_id'	=> $insert,
					]);

					$return = [
						'status'	=> 'success',
						'title'		=> 'alert.success',
						'message'	=> 'alert.your_account_has_been_created',
						'alert_type'=> 'toast',
						'form_reset'=> true,
						'reload'	=> [base('login'), 3]
					];

				} else {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.your_account_could_not_be_created',
						'alert_type'=> 'toast'
					];
				}


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.' . ($get->u_name == $username ? 'already_created_this_username' : 'already_created_this_email'),
					'alert_type'=> 'toast'
				];

			}

		} else {

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

		}

		Response::out($return);

	}

	public function verify () {

		$return = [];

		extract(in([
			'verify_token'	=> 'nulled_text',
		], $_POST));

		if ($verify_token) {

			// Get user data
			$get = (new Database)
				->table('users')
				->select('id, token, status')
				->grouped(function($q) use ($verify_token) {
					$q->where('token', $verify_token)->notWhere('status', 'deleted');
				})
				->get();

			if ($get) {

				if ($get->status == 'passive') {

					$update = (new Database)
						->table('users')
						->where('id', $get->id)
						->update([
							'status'	=> 'active',
							'token'		=> $this->createToken()
						]);

					if ($update) {

						if (isset($_SESSION['user']->id) !== false AND $_SESSION['user']->id == $get->id) {
							$_SESSION['user']->status = 'active';
						}

						$return = [
							'status'	=> 'success',
							'title'		=> 'alert.success',
							'message'	=> 'alert.your_account_has_been_verified',
							'alert_type'=> 'toast',
							'form_reset'=> true,
							'reload'	=> [base((isset($_SESSION['user']->id) !== false ? 'account' : 'login')), 3]
						];

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.your_account_could_not_be_verified',
							'alert_type'=> 'toast'
						];
					}

				} else {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.this_account_already_verified',
						'alert_type'=> 'toast'
					];

				}

			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.user_not_found',
					'alert_type'=> 'toast'
				];

			}

		} else {

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

		}

		Response::out($return);

	}

	public function createToken() {

		return tokenGenerator(48);
	}

	public function clearSessions($id) {

		return (new Database)->table('sessions')
			->where('user_id', $id)
			->delete();

	}

	public function recovery () {

		$return = [];

		extract(in([
			'password'		=> 'nulled_text',
			'email'			=> 'nulled_text',
			'verify_token'	=> 'nulled_text'
		], $_POST));

		if ($email) { // STEP 1

			// Get user data
			$get = (new Database)
				->table('users')
				->select('id, email, u_name, token, status')
				->grouped(function($q) use ($email) {
					$q->where('email', $email)->notWhere('status', 'deleted');
				})
				->get();

			if ($get) {
				
				if ($get->status != 'active') {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.cant_send_recover_link_unverified',
						'alert_type'=> 'toast'
					];

				} else {

					$sendRecoverLink = (new Notification)::create('recovery', [
							'u_name'	=> $get->u_name,
							'email'		=> $get->email,
							'token'		=> $get->token,
							'user_id'	=> $get->id
						]);

					if ($sendRecoverLink) {

						$return = [
							'status'	=> 'success',
							'title'		=> 'alert.success',
							'message'	=> 'alert.sent_password_reset_link',
							'alert_type'=> 'toast',
							'form_reset'=> true,
						];

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.failed_send_password_reset_link',
							'alert_type'=> 'toast'
						];
					}

				}


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.account_not_found',
					'alert_type'=> 'toast'
				];

			}

		} elseif ($verify_token AND $password) { // STEP 2

			// Get user data
			$get = (new Database)
				->table('users')
				->select('id, email, u_name, token, status')
				->grouped(function($q) use ($verify_token) {
					$q->where('token', $verify_token)->notWhere('status', 'deleted');
				})
				->get();

			if ($get) {
				
				if ($get->status != 'active') {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.cant_reset_password_unverified',
						'alert_type'=> 'toast'
					];

				} else {

					$update = (new Database)
						->table('users')
						->where('id', $get->id)
						->update([
							'password'	=> password_hash($password, PASSWORD_DEFAULT),
							'token'		=> $this->createToken()
						]);

					if ($update) {

						$this->clearSessions($get->id);
						(new Notification)::create('recovery_completed', [
							'u_name'	=> $get->u_name,
							'email'		=> $get->email,
							'user_id'	=> $get->id
						]);

						$return = [
							'status'	=> 'success',
							'title'		=> 'alert.success',
							'message'	=> 'alert.password_has_been_reset',
							'alert_type'=> 'toast',
							'form_reset'=> true,
							'reload'	=> [base('login'), 3]
						];

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.failed_resetting_password',
							'alert_type'=> 'toast'
						];
					}

				}


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.account_not_found',
					'alert_type'=> 'toast'
				];

			}

		} else {

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

		}

		Response::out($return);

	}

	public function account () {

		$return = [];

		extract(in([
			'u_name'	=> 'nulled_text',
			'email'		=> 'nulled_text',
			'f_name'	=> 'nulled_text',
			'l_name'	=> 'nulled_text',
			'b_date'	=> 'nulled_text',
			'password'	=> 'nulled_text'
		], $_POST));

		if ($u_name AND $email AND $f_name AND $l_name) {

			// Get user data
			$get = (new Database)
				->table('users')
				->select('id, u_name, f_name, l_name, email, b_date, token, status')
				->where('id', $_SESSION['user']->id)
				->get();

			if ($get) {

				$update = [];
				$alert = false;

				// u_name
				if ($u_name != $get->u_name) {

					$getU = (new Database)
					->table('users')
					->select('id, u_name')
					->where('u_name', $u_name)
					->get();

					if ($getU) {
						$alert = 'already_created_this_username';
					} else {
						$update['u_name'] = $u_name;
					}

				}

				// email
				if ($email != $get->email) {

					if ($get->status != 'active') {

						$alert = 'cant_change_email_unverified';

					} else {

						$getU = (new Database)
						->table('users')
						->select('id, email')
						->where('email', $email)
						->get();

						if ($getU) {
							$alert = 'already_created_this_email';
						} else {
							$update['email'] = $email;
							$update['status'] = 'passive';
						}
					}
				}

				// f_name
				if ($f_name AND $f_name != $get->f_name) {
					$update['f_name'] = $f_name;
				}

				// l_name
				if ($l_name AND $l_name != $get->l_name) {
					$update['l_name'] = $l_name;
				}

				// b_date
				if ($b_date AND strtotime($b_date) != $get->b_date) {
					$update['b_date'] = strtotime($b_date);
				}

				// password
				if ($password) {

					if ($get->status != 'active') {

						$alert = 'cant_change_password_unverified';

					} else {

						$update['password'] = password_hash($password, PASSWORD_DEFAULT);
					}
				}
				
				if (! $alert) {

					if (count($update)) {

						$update['updated_at'] = time();
						$update['updated_by'] = $_SESSION['user']->id;

						$updateAccount = (new Database)
							->table('users')
							->where('id', $_SESSION['user']->id)
							->update($update);

						if ($updateAccount) {

							// Send verification link if email address has changed
							if (isset($update['email']) !== false) {
								(new Notification)::create('verify_email', [
									'u_name'	=> (isset($update['u_name']) !== false ? $update['u_name'] : $get->u_name),
									'email'		=> $update['email'],
									'token'		=> $get->token,
									'user_id'	=> $get->id
								]);
							}

							// Update session
							if (isset($update['password']) !== false) unset($update['password']);
							foreach($update as $key => $value) $_SESSION['user']->{$key} = $value;

							$return = [
								'status'	=> 'success',
								'title'		=> 'alert.success',
								'message'	=> 'alert.profile_updated',
								'alert_type'=> 'toast',
								'reload'	=> [null, 3]
							];

						} else {

							$return = [
								'status'	=> 'warning',
								'title'		=> 'alert.warning',
								'message'	=> 'alert.failed_profile_updated',
								'alert_type'=> 'toast'
							];
						}

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.no_changes_to_save',
							'alert_type'=> 'toast'
						];

					}

				} else {

					$return = [
						'status'	=> 'warning',
						'title'		=> 'alert.warning',
						'message'	=> 'alert.' . $alert,
						'alert_type'=> 'toast'
					];
				}


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.account_not_found',
					'alert_type'=> 'toast'
				];

			}

		} else {

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

		}

		Response::out($return);

	}

}