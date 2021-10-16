<?php

namespace app\core\helpers;

class Response {

	function __construct() {
		
	}

	public static function out ($return) {

		if (isset($return['title']) !== false) {
			$return['title'] = lang($return['title']);
		}

		if (isset($return['message']) !== false) {
			$return['message'] = lang($return['message']);
		}

		echo json_encode($return);

	}

}