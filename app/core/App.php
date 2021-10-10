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
        if ($this->request[0] === 'script') {

            try {

                require path('app/core/external/script.php');
                
            } catch (\Throwable $t) {

                errorHandler (
                    $t->getCode(), 
                    $t->getMessage(), 
                    $t->getFile(),
                    $t->getLine()
                );

            }

        } elseif ($this->request[0] === 'sandbox') {

            try {

                require path('app/core/external/sandbox.php');
                
            } catch (\Throwable $t) {

                errorHandler (
                    $t->getCode(), 
                    $t->getMessage(), 
                    $t->getFile(),
                    $t->getLine()
                );

            }

        } else {

            echo $this->output();
        }
    }

    /**
     * Response output
     * 
     **/
    public function output() {

        $this->route->go();

    }
}