<?php

namespace Controllers\Utils;

class CSRF {
	public static function generate_token() {
		$token = bin2hex(random_bytes(32));
		$_SESSION['csrf_token'] = $token;
		return $token;
	}

	public static function validate_token($token) {
		if (!isset($_SESSION['csrf_token'])) return false;
		if ($_SESSION['csrf_token'] !== $token) return false;
		return true;
	}
}