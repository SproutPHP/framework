<?php
define('SPROUT_START', microtime(true));

/**
 * Enable error reporting (Only in DEV)
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Autoloader
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Starting the session after autoload
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(config('app.session.name', 'sprout_session'));
    session_start();
}

/**
 * Bootstrap the framework
 */
require_once __DIR__ . '/../core/Bootstrap/bootstrap.php';
