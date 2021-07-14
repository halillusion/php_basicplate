<?php

namespace app\core;

use app\core\Core;

/**
 * App Class
 * 
 **/

class App extends Core
{

    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $param = [];
    public $url = '';
    public $request = [];

    public function __construct()
    {

        $url = parse_url(base() . trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($url['path'], '/');

        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];
        $this->request = array_map('urldecode', $this->request);

    }

    /**
     * Start app
     *
     * @return void
     */
    public function start()
    {   
        echo '<pre>';
        var_dump($this->request);
        echo '</pre>';
    }
}