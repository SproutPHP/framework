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
    'framework' => 'SproutPHP',
    'repo' => env('SPROUT_REPO', 'SproutPHP/framework'),
    'user_agent' => env('SPROUT_USER_AGENT', 'sproutphp-app'),

    /**
     * Global middleware
     * Recommended Order
     * 1. VerifyCsrfToken: Blocks malicious requests as early as possible
     * 2. XssProtection: Set security headers before output
     * 3. CorsMiddleware: Set CORS headers after CSRF/XSS checks
     * NOTE: Other middlewares should be kept in after this order.
     */

    'global_middleware' => [
        \App\Middlewares\VerifyCsrfToken::class,
        \App\Middlewares\XssProtection::class,
        \App\Middlewares\CorsMiddleware::class,
    ],

    // Session settings
    'session' => [
        'name' => env('SESSION_NAME', 'sprout_session'),
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
