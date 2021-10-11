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

$route->get([

    // WEB
    ''              => ['AppController/view'     => 'index'],
    'login'         => ['UserController/view'    => 'login'],
    'register'      => ['UserController/view'    => 'register'],
    'recover'       => ['UserController/view'    => 'recover'],
    'page/_'        => ['PageController/view'    => 'page/x'],

    // API
    'api'           => ['ApiController/view'     => 'index'],
    'api/login'     => ['ApiController/view'     => 'login'],
    'api/register'  => ['ApiController/view'     => 'register'],
    'api/page/_'    => ['ApiController/view'     => 'page/x'],

]);

$route->post([

    'user/login'    => ['UserController/login'      => null],
    'user/register' => ['UserController/register'   => null],
    'user/recover'  => ['UserController/recover'    => null],

]);

return $route;