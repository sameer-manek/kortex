<?php

// loading composer modules
require(__DIR__.'/vendor/autoload.php');

// loading local modules
// func to recursively lookup for a pattern in a directory
function glob_recursive($pattern, $flags = 0) {
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
	}
	return $files;
}

$index_directories = [
	"/controllers/",
	"/data/",
];

foreach($index_directories as $dirname) {
	foreach(glob_recursive(__DIR__.$dirname.'**.php') as $filename) {
		require_once($filename);
	}
}

// load debugbar
//use Data\Config;
//use DebugBar\StandardDebugBar;
//
//if (Config::$env === "dev") {
//	// load debugbar
//
//	Config::$debugbar = new StandardDebugBar();
//	Config::$debugbar["messages"]->addMessage("hello world!");
//}

?>
