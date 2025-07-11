<?php

use App\Middlewares\RouteTestMiddleware;
use Core\Routing\Route;

Route::get('/', function () {
    return view('home', ['title' => 'SproutPHP Home']);
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
Route::get('/envtest', function () {
    debug(env('APP_ENV', 'default_env'));
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
