<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\Core;

class AppController extends Core {

	function __construct() {
		
	}

	public function view ($key = null) {

		global $pageStructure, $title, $description;

		$title = lang('page.' . $key);
		$description = lang('page.' . $key . '_desc');

		if (is_array($pageStructure)) {
			
			if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
				echo '<title>'.title(false).'</title><body id="wrap">';
			}

			foreach ($pageStructure as $part) {

				if ($part == '_') { // Content File

					require path('app/views/app/'.$key.'.php');

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

	public function contactForm () {

		$return = [];

		extract(in([
			'type'		=> 'nulled_text',
			'subject'	=> 'nulled_text',
			'message'	=> 'nulled_text'
		], $_POST));

		if ($type AND $subject AND $message) {

			$insert = (new Database)
				->table('contact')
				->insert([
					'type'		=> $type,
					'subject'	=> $subject,
					'message'	=> $message,
					'ip'		=> getIP(),
					'header'	=> getHeader(),
					'created_at'=> time(),
					'created_by'=> (isset($_SESSION['user']->id) !== false ? $_SESSION['user']->id : 0)
				]);

			if ($insert) {

				$return = [
					'status'	=> 'success',
					'title'		=> 'alert.success',
					'message'	=> 'alert.contact_form_saved',
					'alert_type'=> 'toast',
					'form_reset'=> true
				];


			} else {

				$return = [
					'status'	=> 'warning',
					'title'		=> 'alert.warning',
					'message'	=> 'alert.contact_form_not_saved',
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