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
    echo "<h2>Security Configuration Test</h2>";
    echo "<p><strong>XSS Protection:</strong> " . (config('security.xss.enabled') ? 'Enabled' : 'Disabled') . "</p>";
    echo "<p><strong>XSS Mode:</strong> " . config('security.xss.mode') . "</p>";
    echo "<p><strong>CSP Enabled:</strong> " . (config('security.csp.enabled') ? 'Enabled' : 'Disabled') . "</p>";
    echo "<p><strong>CSP Report Only:</strong> " . (config('security.csp.report_only') ? 'Yes' : 'No') . "</p>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>Debug Mode:</strong> " . (config('app.debug') ? 'Enabled' : 'Disabled') . "</p>";

    // Test inline styles (should work in local, blocked in production)
    echo "<p style='color: red;'>This text should be red in local environment</p>";
});

Route::get('/debug-config', function () {
    echo "<h2>Config Debug</h2>";
    echo "<p>Config function exists: " . (function_exists('config') ? 'Yes' : 'No') . "</p>";
    echo "<p>App config loaded: " . (config('app.name') ? 'Yes' : 'No') . "</p>";
    echo "<p>Global middleware count: " . count(config('app.global_middleware', [])) . "</p>";
    foreach (config('app.global_middleware', []) as $middleware) {
        echo "<p>Middleware: $middleware (exists: " . (class_exists($middleware) ? 'Yes' : 'No') . ")</p>";
    }
});

Route::get('/config-test', function () {
    echo "<h2>Configuration Test</h2>";
    echo "<p><strong>App Name:</strong> " . config('app.name') . "</p>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>Debug:</strong> " . (config('app.debug') ? 'true' : 'false') . "</p>";
    echo "<p><strong>Database Host:</strong> " . config('database.connections.mysql.host') . "</p>";
    echo "<p><strong>Twig Cache:</strong> " . (config('view.twig.cache') ? 'true' : 'false') . "</p>";
});

Route::get('/envtest', function () {
    debug(config('app.env', 'default_env'));
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
