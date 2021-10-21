<?php

namespace app\core\helpers;

use app\core\Database;

class Notification {

	function __construct() {
	}

	public static function create (string $type, array $data) {

		switch ($type) {

			case 'register':

				$link = urlGenerator('verify', [], ['token' => $data['token']]);

				$title = lang('notification.register_title');
				$mailBody = str_replace(['[USER]', '[LINK]'], [$data['u_name'], $link], lang('notification.register_mail_body'));


				$insert = [
					'date'	=> time(),
					'email'	=> $data['email'],
					'name'	=> $data['u_name']
				];

			break;
		}

	}

}