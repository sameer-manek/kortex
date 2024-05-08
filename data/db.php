<?php

namespace Data;

use Swoole\Coroutine\MySQL;

/*
 * DB class
 * 
 * This class is connecting to MySQL database using Swoole Coroutine MySQL.
 * Current implementation is slow, and needs to be optimized.
*/

class DB {
	private $conn;

	// todo: store last inserted record
	// todo: connection pooling

	public function __construct() {
		$this->conn = new MySQL();
		$this->conn->connect([
			'host' => Config::DBHOST,
			'port' => Config::DBPORT,
			'user' => Config::DBUSER,
			'password' => Config::DBPASS,
			'database' => Config::DBNAME
		]);
	}

	public function query($query, ...$query_vars) {
        $statement = $this->conn->prepare($query);
        if ($statement == false) {
            $log = new Log(date('Y-m-d H:i:s'), 'ERROR', 'prepare statement', $this->conn->error);
			$log->write_to_file(__DIR__.'/logs/db.log');
			throw new \Exception($this->conn->error);
        }

        $result = $statement->execute($query_vars);
        if ($result === false) {
			$log = new Log(date('Y-m-d H:i:s'), 'ERROR', 'execute statement', $this->conn->error);
			$log->write_to_file(__DIR__.'/logs/db.log');
            throw new \Exception($this->conn->error);
        }

		return $result;
	}

	public function __destruct() {
		// close connection
		$this->conn->close();
	}
}