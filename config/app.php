<?php

return [
    // Application settings
    'name' => env('APP_NAME', 'SproutPHP'),
    'env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'en'),
    'key' => env('APP_KEY', 'your-secret-key-here'),
    
    // Framework settings
    'version' => '1.0.0',
    'framework' => 'SproutPHP',
    'repo' => env('SPROUT_REPO', 'SproutPHP/framework'),
    'user_agent' => env('SPROUT_USER_AGENT', 'sproutphp-app'),
    
    // Global middleware
    'global_middleware' => [
        \App\Middlewares\VerifyCsrfToken::class,
        \App\Middlewares\XssProtection::class,
    ],
    
    // Session settings
    'session' => [
        'driver' => env('SESSION_DRIVER', 'file'),
        'lifetime' => env('SESSION_LIFETIME', 120),
        'path' => env('SESSION_PATH', '/storage/sessions'),
    ],
    
    // Logging
    'log' => [
        'driver' => env('LOG_DRIVER', 'file'),
        'level' => env('LOG_LEVEL', 'debug'),
        'path' => env('LOG_PATH', '/storage/logs'),
    ],
];
