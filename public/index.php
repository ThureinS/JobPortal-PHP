<?php

require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

$router = new Router();

require basePath('route.php');

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
