<?php

namespace app\middlewares;

/**
 * Form CSRF Middleware
 * 
 **/

class Form
{
    public $routes = [];


    public function __construct()
    {

    }

    public static function checkCSRF () {

    	extract(in([
			'token'	=> 'nulled_text'
		], $_POST));

		if ($token AND verifyCSRF($token)) {

			return true;

		} else {

			return 'form_token_invalid';

		}

    }
}