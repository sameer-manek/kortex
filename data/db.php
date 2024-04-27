<?php

namespace Data;

class DB {
	private $conn;

	public function __construct() {
		// create connection
	}

	public function run_query($query, $types, ...$query_vars) {
		
	}

	public function __destruct() {
		// close connection
	}
}