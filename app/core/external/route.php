<?php

use app\core\Route;

$route = new Route();

$route->get([

    // WEB
    ''             => ['AppController/view'     => 'index'],
    'login'        => ['UserController/view'    => 'login'],
    'register'     => ['UserController/view'    => 'register'],
    'page/_'       => ['PageController/view'    => 'page/x'],

    // API
    'api'          => ['ApiController/view'     => 'index'],
    'api/login'    => ['ApiController/view'     => 'login'],
    'api/register' => ['ApiController/view'     => 'register'],
    'api/page/_'   => ['ApiController/view'     => 'page/x'],

]);

return $route;