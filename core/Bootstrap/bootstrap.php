<?php 

/**
 * Load router
 */
use Core\Routing\Router;
use Core\Http\Request;
use Core\Routing\Route;
use Core\Error\ErrorHandler;

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