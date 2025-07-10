<?php 

/**
 * Load router
 */

use Core\Bootstrap\LoadRoutes;
use Core\Routing\Router;
use Core\Http\Request;
use Core\Routing\Route;
use Core\Error\ErrorHandler;

$router = new Router();
$request = Request::capture();
Route::$router = $router;
ErrorHandler::register();

/**
 * Load routes
 */
LoadRoutes::boot();

$router->dispatch($request);