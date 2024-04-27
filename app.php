<?php

use Swoole\Http\Server;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Controllers\Http\Response;
use Data\Log;

$server = new Server("localhost", 8080);

$server->on("start", function ($server) {
	$log = new Log(
		date('Y-m-d H:i:s'), 
		"INFO", 
		"Server Started", 
		"Server was started on PORT 8080"
	);
	$log->write_to_file(__DIR__."/data/logs/server.log");

	error_log("Swoole HTTP server is started at http://127.0.0.1:8080\n");
});

$server->on("request", function (SwooleRequest $request, SwooleResponse $response) use ($router) {

	if (!$router) return;
	
	// cast request and response to our classes
	$response = new Response($response);
	
	if (!$router->resolve($request, $response)) {
		$response->swoole->status(404);
		$response->swoole->end("Not found");
	}
});

$server->start();
