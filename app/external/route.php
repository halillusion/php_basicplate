<?php

use app\core\Route;

$route = new Route();

$route->get([

    // WEB
    ''             => function () { return (new app\controllers\AppController()); },
    'login'        => function () { return view('login'); },
    'register'     => function () { return view('register'); },
    'page/_'       => function () { return view('welcome'); },

    // API
    'api'          => function () { return view('api_home'); },
    'api/login'    => function () { return (new Api('login')); },
    'api/register' => function () { return (new Api('register')); },
    'api/page/_'   => function () { return (new Api('page')); },

]);

return $route;