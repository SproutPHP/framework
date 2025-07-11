<?php

use Core\Routing\Route;

Route::get('/', function () {
    return view('home', ['title' => 'SproutPHP Home']);
});

Route::get('/home', 'HomeController@index');

Route::post('/toggle-theme', function () {
    $currentTheme = $_COOKIE['theme'] ?? 'light';
    $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
    
    // Set cookie for theme preference
    setcookie('theme', $newTheme, time() + (365 * 24 * 60 * 60), '/');
    
    // Return updated navbar with correct theme and icon
    return view('Components/navbar', ['theme' => $newTheme]);
});

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
