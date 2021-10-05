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

	public function post($route) {

		$this->routes['GET'] = $route;

	}

	public function go() {

		if (isset($this->routes[$this->method][$this->url]) !== false) {

			foreach ($this->routes[$this->method][$this->url] as $method => $variable) {

				if (strpos($method, '/') !== false) {

					$class = explode('/', $method, 2);

				} else {

					$class = [$method];

				}

				if (count($class) === 1) {

					$class = 'app\\controllers\\'.$class[0];
					return (new $class($variable));

				} else {

					$method = $class[1];
					$class = 'app\\controllers\\'.$class[0];
					return (new $class)->$method($variable);
				}

			}

		} else {

			view('404');

		}

	}
}