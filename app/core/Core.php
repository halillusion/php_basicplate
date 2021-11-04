<?php

namespace app\core;

/**
 * Core Class
 * 
 **/

class Core
{
    /**
     * App Core
     *
     * @return this
     */
    public $currentLang = 'en';
    public $availableLangs = ['en', 'tr'];
    public $viewFolder = '';
    
    public function __construct()
    {
        global $currentLang;
        global $languages;
        global $changelog;

        try {

            // Loading routes
            $this->route = include path('app/core/external/route.php');

            // Request is parsing
            $request = parse_url(base($_SERVER['REQUEST_URI']));
            $request = trim($request['path'], '/');
            
            $this->request = strpos($request, '/') !== false ? explode('/', $request) : [$request];

            // Language is detecting
            if (isset($_SESSION['lang']) !== false ANd in_array($_SESSION['lang'], $this->availableLangs)) {

                $currentLang = $_SESSION['lang'];

            } else {

                $currentLang = $this->currentLang;
                $_SESSION['lang'] = $currentLang;

            }

            // Language file is importing
            $languages = include path('app/core/localization/'.$currentLang.'.php');

            // Loading changelog
            $changelog = include path('app/core/external/changelog.php');

            http('powered_by');
            session_name(config('app.session'));
            session_start();
            ob_start();
            
        } catch (\Throwable $t) {

            errorHandler (
                $t->getCode(), 
                $t->getMessage(), 
                $t->getFile(),
                $t->getLine()
            );

        }

    }

    
    public function meta ()
    {

    }

}