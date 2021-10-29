<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\helpers\Session;
use app\core\helpers\Notification;

class UserController {

	function __construct() {
		
	}

	public function view ($key = null) {

		global $pageStructure, $title;

		$title = $key;

		if (is_array($pageStructure)) {
			
			if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
				echo '<title>'.title(false).'</title><body id="wrap">';
			}

			foreach ($pageStructure as $part) {

				if ($part == '_') { // Content File

					require path('app/views/user/'.$key.'.php');

				} elseif (file_exists(path('app/views/'.$part.'.php'))) { // Layout File

					if (
						isset($_SERVER['HTTP_X_VPJAX']) === false OR 
						in_array($part, ['inc/header', 'inc/end']) === false) {

						require path('app/views/'.$part.'.php');
					}

				}

			}

			if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
				echo '</body>';
			}
			
		}

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

					if ($insert) {

						$return = [
							'status'	=> 'success',
							'title'		=> 'alert.success',
							'message'	=> 'alert.your_account_has_been_created',
							'alert_type'=> 'toast',
							'form_reset'=> true,
						];

					} else {

						$return = [
							'status'	=> 'warning',
							'title'		=> 'alert.warning',
							'message'	=> 'alert.your_account_could_not_be_created',
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

			$return = [
				'status'	=> 'danger',
				'title'		=> 'alert.error',
				'message'	=> 'alert.form_cannot_empty',
				'alert_type'=> 'toast'
			];

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