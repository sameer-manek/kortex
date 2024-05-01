<?php

namespace Controllers\Http;

class Router {
	private $routes = [];

	public function get($path, $handler, $middlewares = []) {
		$this->routes['GET'][$path]['handler'] = $handler;
		$this->routes['GET'][$path]['middlewares'] = $middlewares;
	}

	public function post($path, $handler, $middlewares = []) {
		$this->routes['POST'][$path]['handler'] = $handler;
		$this->routes['POST'][$path]['middlewares'] = $middlewares;
	}

	public function put($path, $handler, $middlewares = []) {
		$this->routes['PUT'][$path]['handler'] = $handler;
		$this->routes['PUT'][$path]['middlewares'] = $middlewares;
	}

	public function delete($path, $handler, $middlewares = []) {
		$this->routes['DELETE'][$path]['handler'] = $handler;
		$this->routes['DELETE'][$path]['middlewares'] = $middlewares;
	}

	public function patch($path, $handler, $middlewares = []) {
		$this->routes['PATCH'][$path]['handler'] = $handler;
		$this->routes['PATCH'][$path]['middlewares'] = $middlewares;
	}

	private function process_middlewares($names, $request, $response) {
		foreach ($names as $name) {
			if (method_exists('Controllers\Middlewares', $name)) {
				'Controllers\Middlewares'::$name($request, $response);
			} else {
				$response->swoole->status(500);
				$log = new Data\Log(date("Y-m-d H:i:s", "ERROR", "Middleware Not Found", "Middleware $name not found."));
				$log->write_to_file(__DIR__."/../data/logs/errors.log");
				$response->swoole->end("There was an error processing the request.");
				break;
			}
		}
	}

	public function resolve($request, $response) {
		$method = $request->swoole->server['request_method'];
		$uri = $request->swoole->server['request_uri'];
	
		// process all the middlewares
		$middlewares = [];
		$handler = false;
		$params = [];
	
		// regex black magic to match the routes and extract the parameters
		foreach ($this->routes[$method] as $route => $route_data) {
			$pattern = preg_replace_callback('/{(\w+)}/', function ($matches) {
				return '(?P<' . $matches[1] . '>[^\/]+)';
			}, $route);
			$pattern = '@^' . $pattern . '$@';
			if (preg_match($pattern, $uri, $matches)) {
				$params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
				$middlewares = $route_data['middlewares'] ?? [];
				$handler = $route_data['handler'] ?? false;
				break;
			}
		}
	
		$this->process_middlewares($middlewares, $request, $response);
	
		if(!$handler) {
			return false;
		}
	
		// Add the parameters to the request object
		$request->add_data('params', $params);
	
		if (is_callable($handler)) {
			$handler($request, $response);
		} else {
			list($controller, $method) = explode('@', $handler);
			$controller_instance = new $controller();
			$controller_instance->$method($request, $response);
		}
	}
}
