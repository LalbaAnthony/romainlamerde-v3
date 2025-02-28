<?php

require_once 'init.inc.php';

$seeder = new Seeder();
$seeder->crawl();

print "Database seeded successfully.\n";