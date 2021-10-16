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
	'register'				=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['register']]
	],
	'recover'				=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['recover']]
	],

	// FORM
	'form/user/login'		=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::login' => []]
	],

]);

return $route;