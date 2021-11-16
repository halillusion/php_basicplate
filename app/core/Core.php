<?php

namespace app\core;

use app\middlewares\Auth;

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
    public $title = null;
    public $description = null;
    public $keywords = null;
    
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

    public function auth() {
        return new Auth();
    }

    public function activeLink($route = '', $exact = true) {

        global $activeRoute;

        if (
            ( $exact AND $route == $activeRoute ) OR
            ( ! $exact AND strpos($activeRoute, $route) !== false )
        ) {

            return ' active';
        }

    }

    
    public function meta($echo = false)
    {
        $metaTags = PHP_EOL.'
        <meta name="description" content="'.$this->description().'">
        <meta name="author" content="____">
        <meta name="copyright" content="____">
        <meta property="og:title" content="____" /> <!-- Page title -->
        <meta property="og:site_name" data-page-subject="true" content="____" /> <!-- Site name -->
        <meta property="og:url" content="http://____.com" /> <!-- OpenGraph MetaData - Best Link -->
        <meta property="og:description" name="description" content="____" /> <!-- Website description again --> 
        <meta property="og:type" content="article" /> <!-- Content can also be "website" or others. See: http://ogp.me -->
        <meta property="article:published_time" content="2013-09-17T05:59:00+01:00" /> <!-- When the article was first published. -->
        <meta property="article:modified_time" content="2013-09-16T19:08:47+01:00" /> <!-- When the article was last changed. -->
        <meta property="article:expiration_time" content="2020-01-16T19:08:47+01:00" /> <!-- When the article is out of date after. -->
        <meta property="article:section" content="____" />  <!-- A high-level section name. E.g. Technology -->
        <meta property="article:tag" content="____ ____ ____" /> <!-- Tag words associated with this article. -->
        <meta itemprop="name" content="____"> <!-- The Name or Title Here -->
        <meta itemprop="description" content="____"> <!-- This is the page description -->
        <meta name="twitter:card" content="____"> <!-- summary_large_image -->
        <meta name="twitter:site" content="____"> <!-- @username for the website used in the card footer. -->
        <meta name="twitter:title" content="____"> <!-- Page title again -->
        <meta name="twitter:description" content="____"> <!-- Page description less than 200 characters -->
        <meta name="twitter:creator" content="____"> <!-- @username for the content creator / author. -->
        <meta property="og:image" content="https://____.com/image-1024x512.jpg" /> <!-- Generic safe dimension (Landscape). -->
        <meta property="og:image:width" content="1024" />
        <meta property="og:image:height" content="512" />
        <meta name="twitter:image" content="https://____.com/image-506x506.jpg"> <!-- Optimal size image for Twitter Summary with large image card type -->
        <meta name="robots" content="noindex,nofollow">
        <meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34="/>
        '.PHP_EOL;


        if ($echo) echo $metaTags;
        else return $metaTags;

    }

    public function title($echo = false) {

        $return = ($this->title ? $this->title . ' | ' : '') . config('app.name');

        if ($echo) echo $return;
        else return $return;

    }

    public function description($echo = false) {

        $return = ($this->description ? $this->description : '');

        if ($echo) echo $return;
        else return $return;

    }

    public function keywords($echo = false) {

        $return = ($this->keywords ? $this->keywords : '');

        if ($echo) echo $return;
        else return $return;

    }

    public function view($key = null) {

        global $pageStructure;
        
        $this->title = lang('page.' . $key);
        $this->description = lang('page.' . $key . '_desc');

        if (is_array($pageStructure)) {
            
            if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
                echo '<title>'.$this->title(false).'</title><body id="wrap">';
            }

            foreach ($pageStructure as $part) {

                if ($part == '_') { // Content File

                    require path('app/views/'.$this->viewFolder.'/'.$key.'.php');

                } elseif (file_exists(path('app/views/'.$part.'.php'))) { // Layout File

                    if (
                        isset($_SERVER['HTTP_X_VPJAX']) === false OR 
                        in_array($part, ['inc/header', 'inc/end']) === false) {

                        require path('app/views/'.$part.'.php');
                    }

                }

            }

            if (isset($_SERVER['HTTP_X_VPJAX']) !== false) {
                echo '</body>';
            }
            
        }

    }



}