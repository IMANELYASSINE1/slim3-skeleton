<?php

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require_once __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require_once __DIR__ . '/../app/dependencies.php';

// Register middleware
require_once __DIR__ . '/../app/middleware.php';

// Register routes
require_once __DIR__ . '/../app/routes.php';

// Run!
$app->run();
