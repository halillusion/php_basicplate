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

        if ($this->request[0] === 'script') { // Dynamic JS

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

        } elseif ($this->request[0] === 'sandbox' AND config('app.dev_mode')) { // Sandbox for Developers

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

        } else { // HTML Output

            http(200);
            http('content_type', 'html');
            $this->output();
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