<?php

namespace Controllers\Http;

class Request {
	public $data = [];
	public $swoole;

	public function __construct ($request) {
		/*
			Swoole request already captures the data from the request as follows:
				// Extracting GET parameters
				$getParams = $request->get ?? [];
				
				// Extracting POST parameters
				$postParams = $request->post ?? [];
				
				// Extracting headers
				$headers = $request->header ?? [];
				
				// Extracting cookies
				$cookies = $request->cookie ?? [];
				
				// Extracting raw body (for JSON, etc.)
				$rawBody = $request->rawContent();
				
				// Extracting files (if any)
				$files = $request->files ?? [];
		*/
		$this->swoole = $request;
	}

	public function add_data ($key, $value) {
		$this->data[$key] = $value;
	}

	public function get_data ($key) {
		return $this->data[$key] ?? false;
	}
}