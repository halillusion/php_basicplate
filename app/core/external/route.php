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
		'controller' => 'AppController::view(\'index\')', 
		'middlewares' => ''
	],
	'login'					=> [
		'controller' => 'UserController::view(\'login\')', 
		'middlewares' => ['Auth::with(\'nonAuth\')']
	],
	'register'				=> [
		'controller' => 'UserController::view(\'register\')', 
		'middlewares' => ''
	],
	'recover'				=> [
		'controller' => 'UserController::view(\'recover\')', 
		'middlewares' => ''
	],

	// FORM
	'form/user/login'		=> [
		'controller' => 'UserController::login()', 
		'middlewares' => ''
	],

]);

return $route;