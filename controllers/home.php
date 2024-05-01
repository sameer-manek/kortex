<?php

namespace Controllers;

class Home {
	// method: GET
	public function index($req, $res) {
		$res->view("home.twig");
	}

	public function home($req, $res) {
		$res->redirect("/");
	}

	public function err_404($req, $res) {
		$res->swoole->status(404);
		$res->text("Page not found!");
	}
}