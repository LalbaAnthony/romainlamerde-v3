<?php

/**
 * App settings
 */
define('APP_NAME_LONG', 'Romain la Merde');
define('APP_NAME_SHORT', 'RLM');
define('APP_NAME_URL', 'romainlamerde.com');
define('APP_DESCRIPTION', 'Site répertoriant toutes les bêtises dites par Romain et ses compatriotes.');
define('APP_AUTHOR', 'Anthony Lalba');
define('APP_CENSORSHIP', false);
define('APP_VERSION', '3.0.1');
define('APP_LANG', 'fr');


/**
 * App configuration
 */
define('APP_ROOT', '/projects/romainlamerde-v3');
define('APP_URL', 'http://localhost/projects/romainlamerde-v3');
define('APP_DEBUG', true);
define('APP_ENV', 'development'); // 'development' or 'production'
define('APP_SECRET_KEY', 'xxxxxxxxxxxxxxxxxxxxx');

/**
 * Database settings
 */
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'romainlamerde');

/**
 * PHP settings
 */
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
date_default_timezone_set('Europe/Paris');