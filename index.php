<?php
include 'Router.php';

$request = $_SERVER['REQUEST_URI'];
$router = new Router($request);

$router->get('balance', 'api/balance/');
$router->get('event', 'api/event/');