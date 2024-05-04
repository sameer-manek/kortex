<?php

namespace Controllers\Utils\Crypt;

use Data\Config;

function encrypt ($data) {
	$key = Config::KEY;

	// Serialize > Encrypt > Combine > Encode
	$serialized_data = serialize($data);	
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));	
	$encrypted = openssl_encrypt($serialized_data, 'aes-256-cbc', $key, 0, $iv);
	$combined = $iv . $encrypted;
	$encoded = base64_encode($combined);

	return $encoded;
}

function decrypt ($data) {
	$key = Config::KEY;

	// Decode > Separate > Decrypt > Unserialize
	$combined = base64_decode($data);
	$ivLength = openssl_cipher_iv_length('aes-256-cbc');
	$iv = substr($combined, 0, $ivLength);	
	$encrypted_data = substr($combined, $ivLength);
	$decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
	$data = unserialize($decrypted_data);
	
	return $data;
}

function hash ($data) {
	$serialized_data = serialize($data);
	return md5($serialized_data);
}