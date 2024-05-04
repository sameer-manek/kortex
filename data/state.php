<?php

namespace Data;

class CookieManager {
	public static function set($name, $value, $http_only = true, $time = 3600) {
		setcookie($name, $value, time() + $time, "/", "", false, $http_only);
	}

	public static function get($name) {
		return $_COOKIE[$name];
	}

	public static function delete($name) {
		setcookie($name, "", time() - 3600, "/");
	}
}

class SessionManager {
	public static function start() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}
	public static function set($name, $value) {
		$_SESSION[$name] = $value;
	}

	public static function get($name) {
		return $_SESSION[$name];
	}

	public static function delete($name) {
		unset($_SESSION[$name]);
	}

	public static function destroy() {
		session_destroy();
	}
}