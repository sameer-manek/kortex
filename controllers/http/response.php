<?php

namespace Controllers\Http;

use Swoole\Http\Response as SwooleResponse;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Response {
	protected $twig;
	public $swoole;

	public function __construct(SwooleResponse $response) {
		$this->swoole = $response;
		
		// twig setup
		// note: just the setup is costing us 33% of throughput
		// note: rendering with twig eats up another 33%
		// note: in total, twig slows down project by 66%
		// todo: look for twig alternatives
		$loader = new FilesystemLoader('views');
		$this->twig = new Environment($loader);
	}

	public function view($template, $data = []) {
		$content = $this->twig->render($template, $data);

		$this->swoole->header("Content-Type", "text/html");
		$this->swoole->end($content);
	}

	public function json($data = []) {
		$this->swoole->header("Content-Type", "application/json");
		$this->swoole->end(json_encode($data));
	}

	public function text($message = "") {
		$this->swoole->header("Content-Type", "text/plain");
		$this->swoole->end($message);
	}

	public function redirect($route) { // $route:string
		$this->swoole->status(302);
		$this->swoole->header("Location", $route);
		$this->swoole->end();
	}
}