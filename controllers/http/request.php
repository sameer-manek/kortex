<?php

namespace Controllers\Http;

class Request {
	public $data = [];
	public $swoole;

	public function __construct ($request) {
		$this->swoole = $request;
	}

	public function add_data ($key, $value) {
		$this->data[$key] = $value;
	}

	public function get_data ($key) {
		return $this->data[$key] ?? false;
	}
}