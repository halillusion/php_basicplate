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
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) !== false AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'fetch') { // Fetch Responses

			if (isset($return['title']) !== false) {
				$return['title'] = lang($return['title']);
			}

			if (isset($return['message']) !== false) {
				$return['message'] = lang($return['message']);
			}
			http('content_type', json_encode($return), 'application/json');

		} else { // GET Responses

			$message = '';
			if (isset($return['title']) !== false) {
				$message .= lang($return['title']);
			}

			if (isset($return['message']) !== false) {
				$message .= ' ' . lang($return['message']);
			}

			if (isset($return['reload']) !== false) {

				http('refresh', ['second' => $return['reload'][1], 'url' => $return['reload'][0]]);

			}
			http('content_type', 'html');
			echo '<pre>'.$message.'</pre>';

		}

	}

}