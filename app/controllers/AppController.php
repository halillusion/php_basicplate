<?php

namespace app\controllers;

use app\core\Database;
use app\core\helpers\Response;
use app\core\Core;

class AppController extends Core {

	function __construct() {

		$this->viewFolder = 'app';
		
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