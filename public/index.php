<?php

require '../helpers.php';
require basePath('Framework/Database.php');
require basePath('Framework/Router.php');

$router = new Router();

require basePath('route.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
