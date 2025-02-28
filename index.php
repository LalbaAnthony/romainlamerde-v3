<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoloader.php';

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$db = new Database();
Model::setDatabase($db);
Migrator::setDatabase($db);
Seeder::setDatabase($db);

session_start();

$routes = require __DIR__ . '/routes.php';

$request = new App\Http\Request();

$router = new App\Http\Router($request, $routes);
$router->dispatch();
