<?php

/**
 * Load router
 */

use Core\Bootstrap\LoadRoutes;
use Core\Routing\Router;
use Core\Http\Request;
use Core\Routing\Route;
use Core\Error\ErrorHandler;
use Core\Http\Middleware\MiddlewareKernel;

$router = new Router();
$request = Request::capture();
Route::$router = $router;
ErrorHandler::register();

/**
 * Load routes
 */
LoadRoutes::boot();

/**
 * Gloabl Middlewares
 */
$globalMiddleware = [
    // \App\Middlewares\SomeMiddleware::class,
    // Add more middleware here
];

$kernel = new MiddlewareKernel($globalMiddleware);
$response = $kernel->handle($request, function ($request) use ($router) {
    return $router->dispatch($request);
});

echo $response;
