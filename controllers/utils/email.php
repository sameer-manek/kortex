<?php

namespace Controllers\Utils;

use Data\Config;
use Data\Log;

class Email {
	private $to, $subject, $message;

	public function __construct ($to, $subject, $message) {
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
	}

	public function send () {
		// send email
		$log = new Log(date("Y-m-d H:i:s"), "INFO", "Email sent", "sent to $this->to with subject $this->subject");
		$log->write_to_file(__DIR__ . "/../../data/logs/emails.log");
	}

	// push your emails through application job queue.
	public static function enqueue ($to, $subject, $message) {
		// this function will enqueue the email to be processed by the queue
		$task = Queue::enqueue(self::class, array($to, $subject, $message), "send");
	}
}