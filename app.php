<?php

use Swoole\Http\Server;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Controllers\Http\Response;
use Controllers\Http\Request;
use Data\Log;
use Data\SessionManager;

$PORT = 8080;

$server = new Server("localhost", $PORT);

$server->set([
	'reload_async' => true, // hot reload
	'worker_num' => 4,
	'enable_coroutine' => true,
	'log_file' => __DIR__ . '/data/logs/swoole.log',
	// 'daemonize' => true
]);

$server->on("start", function ($server) {
	error_log("Swoole HTTP server is started on localhost\n");
});

$server->on("request", function (SwooleRequest $request, SwooleResponse $response) use ($router) {
	SessionManager::start();
	$uri = $request->server['request_uri'];

	// cast request and response to our classes
	$request = new Request($request);
	$response = new Response($response);

	// handling static files.
	// todo: find a way to serve these files without parsing through router.
	if (strpos($uri, "/static") === 0 || $uri === "/favicon.ico") {
		// If the request is for a static file, read the file and send it as the response
		$filepath = __DIR__ . $uri;
		if (is_file($filepath) && file_exists($filepath)) {
			$mime = Controllers\Utils\Helpers\get_mime_type($filepath);
			$response->swoole->header('Content-Type', $mime);
			$response->swoole->end(file_get_contents($filepath));
			return;
		}
	}

	if (!$router) return;
	
	if (!$router->resolve($request, $response)) {
		$response->swoole->status(404);
		$response->swoole->end("Not found");
	}
});

$server->on("WorkerStart", function ($server, $worker_id) {
	if ($worker_id === 0) {
		error_log("processing queue\n");
		swoole_timer_tick(30000, function() { // every 30 sec
			Controllers\Utils\Queue::class::work();
		});
	}
});

$server->start();
