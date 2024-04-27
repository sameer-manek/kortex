<?php

namespace Controllers\Utils\Crypt;

use Data\Config;

function encrypt ($data) {
	$key = Config::KEY;

	// Serialize the data
	$serialized_data = serialize($data);
	
	// Generate a random initialization vector
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	
	// Encrypt the serialized data using AES-256-CBC
	$encrypted = openssl_encrypt($serialized_data, 'aes-256-cbc', $key, 0, $iv);
	
	// Combine the IV and encrypted data
	$combined = $iv . $encrypted;
	
	// Encode the result in base64 to make it storable
	$encoded = base64_encode($combined);

	return $encoded;
}

function decrypt ($data) {
	$key = Config::KEY;

	// Decode the base64 encoded string
	$combined = base64_decode($data);
	
	// Extract the IV from the combined data
	$ivLength = openssl_cipher_iv_length('aes-256-cbc');
	$iv = substr($combined, 0, $ivLength);
	
	// Extract the encrypted data
	$encrypted_data = substr($combined, $ivLength);
	
	// Decrypt the data using AES-256-CBC
	$decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
	
	// Unserialize the decrypted data
	$data = unserialize($decrypted_data);
	
	return $data;
}

function hash ($data) {
	$serialized_data = serialize($data);
	return md5($serialized_data);
}