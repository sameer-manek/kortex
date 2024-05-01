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