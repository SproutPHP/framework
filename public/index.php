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
 * Setting the session path and starting the session after autoload
 */
$sessionConfig = config('app.session', []);
$sessionDriver = $sessionConfig['driver'] ?? 'file';

if ($sessionDriver === 'file') {
    $sessionPath = $sessionConfig['path'] ?? '/storage/sessions';
    $fullSessionPath = realpath(__DIR__ . '/../') . $sessionPath;
    if (!is_dir($fullSessionPath)) {
        mkdir($fullSessionPath, 0777, true);
    }
    session_save_path($fullSessionPath);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name($sessionConfig['name'] ?? 'sprout_session');
    session_start();
}

/**
 * Bootstrap the framework
 */
require_once __DIR__ . '/../core/Bootstrap/bootstrap.php';
