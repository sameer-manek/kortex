<?php

namespace Data;

use Swoole\Table;

// current implementation is memory constrained.
// [Todo] find a way to delete the oldest cache item when the cache is full.

class Cache
{
	private static $instance;
	private $table;
	private $rows;

	private function __construct($rows = 2000) {
		$this->table = new Table($rows);
		$this->table->column('value', Table::TYPE_STRING, 4096);
		$this->table->column('expiry', Table::TYPE_INT, 32);
		$this->table->create();
		$this->rows = $rows;
	}

	public function manage() {
		foreach ($this->table as $key => $row) {
			if ($row['expiry'] > 0 && $row['expiry'] < time()) {
				error_log("Deleting expired cache key: $key, items count: " . $this->count(). "\n");
				$this->delete($key);
			}
		}
	}

	public static function getInstance($rows = 200000) {
		if (self::$instance === null) {
			error_log("Creating new cache instance");
			self::$instance = new self($rows);
		}
		return self::$instance;
	}

	public function set($key, $value, $expiry = 0) {
		if ($this->count() >= $this->rows) {
			return;
		}
		$this->table->set($key, [
			'value' => serialize($value),
			'expiry' => $expiry > 0 ? time() + $expiry : 0
		]);
	}

	public function get($key) {
		$row = $this->table->get($key);
		if ($row === false) {
			return null;
		}
		return unserialize($row['value']);
	}

	public function truncate() {
		foreach ($this->table as $key => $row) {
			$this->delete($key);
		}
	}

	public function delete($key) {
		$this->table->del($key);
	}

	public function count() {
		return $this->table->count();
	}
}