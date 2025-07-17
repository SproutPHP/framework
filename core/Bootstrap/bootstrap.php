<?php

/**
 * Load router
 */

use App\Middlewares\VerifyCsrfToken;
use App\Middlewares\XssProtection;
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
 * Test config loading
 */
if (!function_exists('config')) {
    throw new \Exception("Config helper function not found. Check if helpers.php is loaded.");
}

/**
 * Gloabl Middlewares
 */
$globalMiddleware = config('app.global_middleware', [
    VerifyCsrfToken::class,
    XssProtection::class,
]);

// Debug middleware loading
if (config('app.debug', false)) {
    foreach ($globalMiddleware as $middleware) {
        if (!class_exists($middleware)) {
            throw new \Exception("Middleware class '$middleware' not found. Check autoloading.");
        }
    }
}

$kernel = new MiddlewareKernel($globalMiddleware);
$response = $kernel->handle($request, function ($request) use ($router) {
    return $router->dispatch($request);
});

echo $response;
