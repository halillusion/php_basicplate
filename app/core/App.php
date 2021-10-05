<?php

namespace app\core;

use app\core\Core;

/**
 * App Class
 * 
 **/

class App extends Core
{

    public function __construct()
    {
        $this->route = include path('app/core/external/route.php');

        $request = parse_url(base($_SERVER['REQUEST_URI']));
        $request = trim($request['path'], '/');
        
        $this->request = strpos($request, '/') !== false ? explode('/', $request) : [$request];

    }

    /**
     * Start app
     *
     * @return void
     */
    public function start()
    {   
        if ($this->request[0] === 'script') {

            echo 'script';

        } elseif ($this->request[0] === 'sandbox') {

            echo 'sandbox';

        } else {

            echo $this->output();
        }
    }
}