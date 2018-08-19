<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$date = new DateTime();
require_once 'vendor/autoload.php';

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
	'username' => 'root',
	'password' => null,
	'db' => *
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800,
        'cart' => array()
    ),
    'session'=> array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'mailer' => array(
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'username' => *,
        'password' => *
    )

);

spl_autoload_register(function($class){
    require_once("classes/".$class.".php");
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name'))){
    $field = DB::getInstance()->get('user_session', array('hash', '=', Cookie::get(Config::get('remember/cookie_name'))));
    $user = new Customer($field->firstResult()->user_id);
    $user->login();
}
