<?php

namespace Controllers\Http;

class Router {
	private $routes = [];

	public function get($path, $handler) {
		$this->routes['GET'][$path] = $handler;
	}

	public function post($path, $handler) {
		$this->routes['POST'][$path] = $handler;
	}

	public function put($path, $handler) {
		$this->routes['PUT'][$path] = $handler;
	}

	public function delete($path, $handler) {
		$this->routes['DELETE'][$path] = $handler;
	}

	public function patch($path, $handler) {
		$this->routes['PATCH'][$path] = $handler;
	}

	public function resolve($request, $response) {
		$method = $request->server['request_method'];
		$uri = $request->server['request_uri'];

		$handler = $this->routes[$method][$uri] ?? false;

		if(!$handler) {
			return false;
		}

		if (is_callable($handler)) {
			$handler($request, $response);
		} else {
			list($controller, $method) = explode('@', $handler);
			$controllerInstance = new $controller();
			$controllerInstance->$method($request, $response);
		}

		return true;
	}
}
