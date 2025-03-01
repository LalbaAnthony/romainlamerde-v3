<?php

use App\Http\Router;
use App\Http\Request;
use App\Database;
use App\Models\Model;
use App\Migrator;
use App\Seeder;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoloader.php';

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Initialize the database and set it to the classes
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
Model::setDatabase($db);
Migrator::setDatabase($db);
Seeder::setDatabase($db);

session_start();

$routes = require_once __DIR__ . '/routes.php';

$request = new Request();

$router = new Router($request, $routes);
$router->dispatch();
