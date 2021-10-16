<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;

class UserController {

	function __construct() {
		
	}

	function view ($key = null) {

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

	function login () {

		$return = [];

		extract(in([
			'password'	=> 'nulled_text',
			'email'		=> 'nulled_text'
		], $_POST));

		if ($password AND $email) {

			$get = (new Database)
				->table('users')
				->select('id, u_name, f_name, l_name, email, password, token, role_id, status')
				->grouped(function($q) use ($email) {
					$q->where('u_name', $email)->orWhere('email', $email);
				})
				->notWhere('status', 'deleted')
				->get();




			if ($get) {

				if (password_verify($password, $get->password)) {



					$return = [
						'status'	=> 'success',
						'title'		=> 'alert.success',
						'message'	=> 'alert.you_are_logged_in',
						'alert_type'=> 'toast',
						'reload'	=> [null, 3]
					];

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

}