<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';
$response = $app->handleRequest(Request::capture());

// Timing debug header
$duration = round((microtime(true) - LARAVEL_START) * 1000, 2);
$response->headers->set('X-Response-Time-Ms', (string)$duration);
$response->send();
