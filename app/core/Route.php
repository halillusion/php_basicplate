<?php

namespace app\core;

/**
 * Route Class
 * 
 **/

class Route
{
    public $routes = [];


    public function __construct()
    {
        $url = parse_url(base() . trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($url['path'], '/');
        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];
        $this->request = array_map('urldecode', $this->request);

    }

	public function addRoutes($routes) {

		$this->routes = $routes;

	}

	public function go() {

		if (isset($this->routes[$this->url]) !== false) {

			$response = null;
			$gateway = true;

			// Middlewares
			if ($this->routes[$this->url]['middlewares']) {

				$middlewares = $this->routes[$this->url]['middlewares'];

				if (count($middlewares)) {

					$gateway = null;
					foreach ($middlewares as $path => $arguments) {

						if (is_null($gateway) OR $gateway) {

							$path = 'app\\middlewares\\'.$path;
							$path = explode('::', $path);

							try {

								$gateway = call_user_func_array(array((new $path[0]), $path[1]), $arguments);

							} catch (Exception $e) {
								throw 'Middleware not found!';
							}
						}
						else break;

					}

				}

			}

			// Controller
			if ($gateway) {

				$controller = $this->routes[$this->url]['controller'];

				foreach ($controller as $path => $arguments) {

					$path = 'app\\controllers\\'.$path;
					$path = explode('::', $path);

					try {

						$response = call_user_func_array(array((new $path[0]), $path[1]), $arguments);
						break;

					} catch (Exception $e) {
						throw 'Middleware not found!';
					}

				}

			} else {

				dump($gateway);

			}

		} else {

			http(404);
			view('404');

		}

	}
}