<?php

use Controllers\Http\Router;

$router = new Router();

// prohibitted routes
$router->get("/data", "Controllers\Home@err_404");
$router->get("/controllers", "Controllers\Home@err_404");

// website routes
$router->get("/", "Controllers\Home@index", ["csrf"]);
$router->get("/home", "Controllers\Home@home");
$router->get("/json", "Controllers\Home@test_json"); // test json response
