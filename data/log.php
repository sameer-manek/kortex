<?php

namespace Data;

use DateTime;
use Swoole\Coroutine\System;

class Log {
	public DateTime $timestamp;
	public $type, $title, $message;

	public function __construct($timestamp, $type, $title, $message) {
		$this->timestamp = new DateTime($timestamp);
		$this->type = $type;
		$this->title = $title;
		$this->message = $message;
	}

	public function write_to_file($filepath) {
		$log_s = $this->timestamp->format("Y-m-d H:i:s")."||$this->type||$this->title||$this->message" . PHP_EOL;
		
		$success = System::writeFile($filepath, $log_s, FILE_APPEND);

		return $success;
	}
}