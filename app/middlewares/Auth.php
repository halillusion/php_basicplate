<?php

namespace app\middlewares;

/**
 * Auth Middleware
 * 
 **/

class Auth
{
    public static $session;


    public function __construct()
    {
        $this::$session = $_COOKIE[config('app.session')];

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
}