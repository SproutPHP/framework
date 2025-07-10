<?php

/**
 * Enable error reporting (Only in DEV)
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

/**
 * Autoloader
 */
require_once __DIR__.'/../vendor/autoload.php';

/**
 * Bootstrap the framework
 */
require_once __DIR__.'/../core/bootstrap.php';