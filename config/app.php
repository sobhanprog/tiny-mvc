<?php
$currentUrl = "http" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

define('APP', 'mvc project');
define('BASE_URL', $currentUrl);
define('BASE_DIRE', realpath(__DIR__ . "/../"));

$temporary = str_replace(BASE_URL, '', explode("?", $_SERVER['REQUEST_URI'])[0]);

$temporary = trim($temporary, "/");

define('CURRENT_ROUTE', $temporary);

global $routes;

$routes = [
    'get' => [],
    'post' => [],
    'put' => [],
    'delete' => []
];

