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

					$gateway = false;
					foreach ($middlewares as $path => $arguments) {

						$path = 'app\\middlewares\\'.$path;
						$path = explode('::', $path);

						try {

							$gateway = call_user_func_array(array((new $path[0]), $path[1]), $arguments);

						} catch (Exception $e) {
							break;
							throw 'Middleware not found!';
						}

					}

				}

			}

			// Controller
			if ($gateway === true) {

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

			} elseif (is_string($gateway)) {

				http('refresh', ['second' => 3, 'url' => base()]);
				echo '<pre>'.lang('alert.'.$gateway).'</pre>';

			} else {

				http('location', base());

			}

		} else {

			http(404);
			view('404');

		}

	}
}