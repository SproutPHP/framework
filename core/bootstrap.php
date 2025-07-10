<?php 

/**
 * Load router
 */
use Core\Router;
use Core\Request;
use Core\Route;
use Core\ErrorHandler;

require_once __DIR__ . '/helpers.php';

$router = new Router();
$request = Request::capture();
Route::$router = $router;
ErrorHandler::register();

/**
 * Load routes
 */
require_once __DIR__.'/../routes/web.php';

$router->dispatch($request);