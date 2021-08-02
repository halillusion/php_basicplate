<?php

namespace app\core;

use app\core\Core;

/**
 * App Class
 * 
 **/

class App extends Core
{

    /**
     * Start app
     *
     * @return void
     */
    public function start()
    {   
        echo $this->output();
    }
}