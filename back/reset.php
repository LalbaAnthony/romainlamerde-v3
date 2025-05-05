<?php

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

if (APP_ENV !== 'development') {
    print "This script can only be run in development mode.\n";
    exit;
}

// Initialize the database and set it to the classes
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
Model::setDatabase($db);
Migrator::setDatabase($db);
Seeder::setDatabase($db);

// Migrate the database
$migrator = new Migrator();
$migrator->crawl();

print "Database migrated successfully.\n";

// Seed the database
$seeder = new Seeder();
$seeder->crawl();

print "Database seeded successfully.\n";
