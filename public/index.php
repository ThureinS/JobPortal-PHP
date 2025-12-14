<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

$router = new Router();

require basePath('route.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$router->route($uri);
