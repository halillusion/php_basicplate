<?php

namespace app\middlewares;

/**
 * Auth Middleware
 * 
 **/

class Auth
{


	public function __construct()
	{

	}

	public static function with($type = 'auth') {

		if ($type == 'auth' AND isset($_SESSION['auth']) !== false AND $_SESSION['auth']) {

			return true;

		} elseif ($type == 'nonAuth' AND (isset($_SESSION['auth']) === false OR ! $_SESSION['auth'])) {

			return true;

		} else {

			return ($type == 'nonAuth' ? 'you_have_an_session' : 'you_have_not_an_session');
		}

	}

	public static function view($point) {

		$return = false;
		if (
			isset($_SESSION['user']->role->view_points) !== false AND 
			in_array($point, $_SESSION['user']->role->view_points) !== false
		) {
			$return = true;
		}
		return $return;

	}
}