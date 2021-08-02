<?php

namespace app\core;

/**
 * Route Class
 * 
 **/

class Route
{

    public $method = 'GET';
    public $routes = [];


    public function __construct()
    {
        $url = parse_url(base() . trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($url['path'], '/');
        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];
        $this->request = array_map('urldecode', $this->request);

    }

	public function get($route) {

		$this->routes['GET'] = $route;

	}

	public function go() {

		if (isset($this->routes[$this->method][$this->url]) !== false) {

			$this->routes[$this->method][$this->url]();

		} else {

			view('404');

		}

	}
}