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

register_shutdown_function( "fatalHandler" );
set_error_handler( "errorHandler" );
set_exception_handler( "exceptionHandler" );
ini_set('display_errors', 'off');
error_reporting(E_ALL);