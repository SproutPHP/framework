<?php

use App\Middlewares\RouteTestMiddleware;
use Core\Routing\Route;

Route::get('/', function () {
    $getLatestRelease = getLatestRelease();
    return view('home', ['title' => 'SproutPHP Home', 'getLatestRelease' => $getLatestRelease]);
});

Route::get('/home', 'HomeController@index');

// Example HTTP method routes (test with Postman or curl)
Route::post('/submit', function () {
    return 'POST received';
});
Route::put('/update', function () {
    return 'PUT received';
});
Route::patch('/edit', function () {
    return 'PATCH received';
});
Route::delete('/delete', function () {
    return 'DELETE received';
});
Route::get('/security-test', function () {
    $data = [
        'xss_enabled' => config('security.xss.enabled'),
        'xss_mode' => config('security.xss.mode'),
        'csp_enabled' => config('security.csp.enabled'),
        'csp_report_only' => config('security.csp.report_only'),
        'env' => config('app.env'),
        'debug' => config('app.debug'),
    ];
    render_fragment_or_full('partials/security-test', $data);
});

Route::get('/debug-config', function () {
    $middlewares = config('app.global_middleware', []);
    $middleware_info = [];
    foreach ($middlewares as $middleware) {
        if (!$middleware) continue; // skip empty values
        $middleware_info[] = [
            'name' => $middleware,
            'exists' => class_exists($middleware),
        ];
    }
    $data = [
        'config_exists' => function_exists('config'),
        'app_config_loaded' => config('app.name'),
        'middleware_count' => count($middleware_info),
        'middlewares' => $middleware_info,
    ];
    render_fragment_or_full('partials/debug-config', $data);
});

Route::get('/config-test', function () {
    $data = [
        'app_name' => config('app.name'),
        'env' => config('app.env'),
        'debug' => config('app.debug'),
        'db_host' => config('database.connections.mysql.host'),
        'twig_cache' => config('view.twig.cache'),
    ];
    render_fragment_or_full('partials/config-test', $data);
});

Route::get('/envtest', function () {
    $data = [
        'env' => config('app.env', 'default_env'),
    ];
    render_fragment_or_full('partials/env-test', $data);
});
Route::get('/crash', function () {
    $a = 10 / 0; // Division by zero
});
Route::get('/boom', function () {
    explodeBanana(); // Undefined function
});
Route::get('/test500', function () {
    throw new Exception("Manual 500 test triggered.");
});
Route::get('/route-middleware-test', function () {
    return 'Route-specific middleware test response.';
})->middleware('route-test');

// 🌱 Resource routes for Validation-testController
Route::get('/validation-test', 'ValidationTestController@index');
Route::post('/validation-test', 'ValidationTestController@handleForm');
