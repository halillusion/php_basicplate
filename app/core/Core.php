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

    protected $currentController = 'App';
    protected $currentMethod = 'index';
    protected $param = [];
    public $url = '';
    public $request = [];
    public $route = null;
    

    public function output() {

        $this->route->go();

    }
}