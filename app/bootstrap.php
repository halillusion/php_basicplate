<?php

define('ROOT_PATH',  rtrim($_SERVER["DOCUMENT_ROOT"], '/').'/');

foreach (glob(ROOT_PATH.'app/core/helpers/*.php') as $file) {
    require_once $file;
}

spl_autoload_register( function($className) {

    $file = ROOT_PATH . str_replace('\\', '/', $className) . '.class.php'; // strtolower() used

    if (file_exists($file)) {

        require_once $file;

    } else {

        $file = ROOT_PATH . str_replace('\\', '/', $className) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }

    }

});