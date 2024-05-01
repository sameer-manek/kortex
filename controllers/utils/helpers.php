<?php

namespace Controllers\Utils\Helpers;

function random_number ($min=1, $max=100) {
	return rand($min, $max);
}

function random_string ($length=10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters_length = strlen($characters);
	$random_string = '';
	for ($i = 0; $i < $length; $i++) {
		$random_string .= $characters[rand(0, $characters_length - 1)];
	}
	return $random_string;
}

function get_mime_type ($filepath) {
	$types = [
		"css" => "text/css",
		"js" => "text/javascript",
		"png" => "image/png",
		"jpg" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"gif" => "image/gif",
		"svg" => "image/svg+xml",
		"ico" => "image/x-icon",
	];
	$ext = pathinfo($filepath, PATHINFO_EXTENSION);
	if (array_key_exists($ext, $types)) {
		return $types[$ext];
	}
	return "text/plain";
}
	