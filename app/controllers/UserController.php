<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\helpers\Session;

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

				$insert = (new Database)
					->table('users')
					->insert([
						'u_name'	=> $username,
						'email'		=> $email,
						'password'	=> password_hash($password, PASSWORD_DEFAULT),
						'token'		=> tokenGenerator(48),
						'role_id'	=> config('settings.default_user_role'),
						'created_at'=> time()
					]);

				if ($insert) {

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

}