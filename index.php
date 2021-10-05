<?php

/**
 * @package  Basicplate based on Kalipso Build Engine
 * @author   halillusion <halillusion@gmail.com>
 */

use app\core\App;

try {

    require __DIR__.'/app/bootstrap.php';

    (new App())->start();
    
} catch (\Throwable $t) {

    errorHandler (
        $t->getCode(), 
        $t->getMessage(), 
        $t->getFile(),
        $t->getLine()
    );

}