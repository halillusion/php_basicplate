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
	'login'					=> [
		'middlewares'	=> ['Auth::with' => ['nonAuth']],
		'controller'	=> ['UserController::view' => ['login']]
	],
	'register'				=> [
		'middlewares'	=> [],
		'controller'	=> ['UserController::view' => ['register']]
	],
	'recover'				=> [
		'middlewares'	=> [],
		'controller'	=> ['UserController::view' => ['recover']]
	],

	// FORM
	'form/user/login'		=> [
		'middlewares'	=> [],
		'controller'	=> ['UserController::login' => []]
	],

]);

return $route;