<?php

use app\core\Route;

$route = new Route();

global $pageStructure;

$pageStructure = [
	'inc/header',
	'inc/nav',
	'_',
	'inc/footer',
	'inc/end',
];

$route->addRoutes([

	// WEB
	''						=> [
		'middlewares'	=> [],
		'controller'	=> ['AppController::view' => ['index']]
	],
	'contact'						=> [
		'middlewares'	=> [],
		'controller'	=> ['AppController::view' => ['contact']]
	],
	'login'					=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['login']]
	],
	'logout'					=> [
		'middlewares'	=> ['Auth::with' => ['auth']],
		'controller'	=> ['UserController::logout' => []]
	],
	'account'					=> [
		'middlewares'	=> ['Auth::with' => ['auth']],
		'controller'	=> ['UserController::view' => ['account']]
	],
	'register'				=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['register']]
	],
	'recover'				=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['recover']]
	],
	'verify'				=> [
		'middlewares'	=> [],
		'controller'	=> ['UserController::view' => ['verify']]
	],

	// FORM
	'form/user/login'		=> [
		'middlewares'	=> ['Form::checkCSRF' => [], 'Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::login' => []]
	],
	'form/user/register'		=> [
		'middlewares'	=> ['Form::checkCSRF' => [], 'Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::register' => []]
	],
	'form/user/recover'		=> [
		'middlewares'	=> ['Form::checkCSRF' => [], 'Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::recovery' => []]
	],
	'form/user/account'		=> [
		'middlewares'	=> ['Form::checkCSRF' => [], 'Auth::with' => ['auth']],
		'controller'	=> ['UserController::account' => []]
	],
	'form/user/verify'		=> [
		'middlewares'	=> ['Form::checkCSRF' => []],
		'controller'	=> ['UserController::verify' => []]
	],
	'form/contact'		=> [
		'middlewares'	=> ['Form::checkCSRF' => []],
		'controller'	=> ['AppController::contactForm' => []]
	],

]);

return $route;