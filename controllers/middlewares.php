<?php

namespace Controllers;

// middlewares are supposed to be simple functions.
// their purpose is to validate / bake the data before request is passed on.
// hence they can be fitted into a single file itself.

class Middlewares {
	// sample middleware (feel free to delete this)
	static function log_request ($request, $response) {
		$log = new \Data\Log(date("Y-m-d H:i:s"), "INFO", "Incoming Request", "There was a request made.");
		$log->write_to_file(__DIR__."/../data/logs/requests.log");
	}
}