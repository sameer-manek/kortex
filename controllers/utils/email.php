<?php

/*
	NOTE: this could be the slowest part of the framework
	I might have to write a custom SMTP mailer in the future.
*/

namespace Controllers\Utils;

use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email as SymfonyEmail;
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
		$mailer = new SymfonyMailer(Transport::fromDsn(Config::MAILER_DSN));
		
		// send email
		if (is_array($this->to)) {
			// send bulk email
			foreach ($this->to as $address) {
				$email = (new SymfonyEmail())
					->from(Config::MAILER_FROM)
					->to($address)
					->subject($this->subject)
					->text($this->message);

				$mailer->send($email);
				unset($email);

				$log = new Log(date("Y-m-d H:i:s"), "INFO", "Sent Email", "sent to $address");
				$log->write_to_file(__DIR__ . "/../../data/logs/emails.log");
				// sleep(1); // sleep to avoid rate limiting
			}
		} else if (is_string($this->to)) {
			// send to single address
			$email = (new SymfonyEmail())
				->from(Config::MAILER_FROM)
				->to($this->to)
				->subject($this->subject)
				->text($this->message);

			$mailer->send($email);
			unset($email);

			$log = new Log(date("Y-m-d H:i:s"), "INFO", "Sent Email", "sent to $this->to");
			$log->write_to_file(__DIR__ . "/../../data/logs/emails.log");
			// sleep(1); // sleep to avoid rate limiting
		} else {
			// invalid email. Log activity
			$log = new Log(date("Y-m-d H:i:s"), "ERROR", "Could not send", "sent to $this->to with subject $this->subject");
			$log->write_to_file(__DIR__ . "/../../data/logs/emails.log");
		}

		unset($mailer);
		return true;
	}

	// push your emails through application job queue.
	public static function enqueue ($to, $subject, $message) {
		// this function will enqueue the email to be processed by the queue
		$task = Queue::enqueue(self::class, array($to, $subject, $message), "send");
	}
}