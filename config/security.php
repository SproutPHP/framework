<?php

return [
    'csrf' => [
        'enabled' => env('CSRF_ENABLED', true),
        'token_name' => env('CSRF_TOKEN_NAME', '_token'),
        'expire' => env('CSRF_EXPIRE', 3600),
    ],

    'xss' => [
        'enabled' => env('XSS_PROTECTION', true),
        'mode' => env('XSS_MODE', 'block'), // 'block', 'sanitize', or '0' to disable
    ],

    'csp' => [
        'enabled' => env('CSP_ENABLED', true),
        'report_only' => env('CSP_REPORT_ONLY', false),
        'report_uri' => env('CSP_REPORT_URI', null),
        // Add connect-src override for CSP (comma-separated domains, e.g. https://api.github.com)
        // Set CSP_CONNECT_SRC in your .env to allow external APIs, e.g.:
        // CSP_CONNECT_SRC=https://api.github.com,https://another.api.com
        'connect_src' => array_filter(array_map('trim', explode(',', env('CSP_CONNECT_SRC', '')))),
        // Add img-src override for CSP (comma-separated domains, e.g. https://img.shields.io)
        // Set CSP_IMG_SRC in your .env to allow external images, e.g.:
        // CSP_IMG_SRC=https://img.shields.io,https://www.gravatar.com
        'img_src' => array_filter(array_map('trim', explode(',', env('CSP_IMG_SRC', '')))),
    ],

    'cors' => [
        'enabled' => env('CORS_ENABLED', false),
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
        'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE')),
        'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization')),
    ],
];