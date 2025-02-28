<?php

require_once 'init.inc.php';

$migrator = new Migrator();
$migrator->crawl();

print "Database migrated successfully.\n";