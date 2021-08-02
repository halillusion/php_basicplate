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

    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $param = [];
    public $url = '';
    public $request = [];
    public $route = null;


    public function __construct()
    {
        $this->route = include path('app/external/route.php');

    }

    public function output() {

        $this->route->go();

    }
}