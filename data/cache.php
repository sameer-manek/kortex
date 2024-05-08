<?php

namespace Data;

use Swoole\Table;

class Cache
{
	private static $instance;
	private $table;

	private function __construct($rows = 2000) {
		$this->table = new Table($rows);
		$this->table->column('key', Table::TYPE_STRING, 256);
		$this->table->column('value', Table::TYPE_STRING, 4096);
		$this->table->column('expiry', Table::TYPE_INT, 32);
		$this->table->create();
	}

	public static function getInstance($rows = 2000) {
		if (self::$instance === null) {
			error_log("Creating new cache instance");
			self::$instance = new self($rows);
		}
		return self::$instance;
	}

	public function set($key, $value, $expiry = 0) {
		$this->table->set($key, [
			'key' => $key,
			'value' => serialize($value),
			'expiry' => $expiry > 0 ? time() + $expiry : 0
		]);
	}

	public function get($key) {
		$row = $this->table->get($key);
		if ($row === false) {
			return null;
		}
		if ($row['expiry'] > 0 && $row['expiry'] < time()) {
			$this->delete($key);
			return null;
		}
		return unserialize($row['value']);
	}

	public function count() {
		return $this->table->count();
	}
}