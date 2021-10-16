<?php

namespace app\core\helpers;

class Response {

	function __construct() {
	}

	/**
	 *	@param object 
	 * 	@return string
	 **/
	public static function out (array $return) {

		/**
		 *  title: response title
		 *  message: response message
		 *  status: (success|warning|danger)
		 *  alert_type: (card|toast|alert)
		 *  reload: [(url | null), seconds]
		 * 
		 * */

		if (isset($return['title']) !== false) {
			$return['title'] = lang($return['title']);
		}

		if (isset($return['message']) !== false) {
			$return['message'] = lang($return['message']);
		}

		echo json_encode($return);

	}

}