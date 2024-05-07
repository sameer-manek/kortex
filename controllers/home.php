<?php

namespace Controllers;

use Data\DB;
use Data\Log;
use Data\Queries;
use Controllers\Utils\Email;

class Home {
	// method: GET
	public function index($req, $res) {
		$res->view("home.twig");
	}

	public function home($req, $res) {
		$res->redirect("/");
	}

	public function test_json($req, $res) {
		$res->json([
			"message" => "Hello, World!"
		]);
	}

	public function err_404($req, $res) {
		$res->swoole->status(404);
		$res->text("Page not found!");
	}
}
