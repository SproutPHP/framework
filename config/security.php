<?php

return [
    'csrf' => [
        'enabled' => env('CSRF_ENABLED', true),
        'token_name' => env('CSRF_TOKEN_NAME', '_token'),
        'expire' => env('CSRF_EXPIRE', 3600),
    ],
    
    'xss' => [
        'enabled' => env('XSS_PROTECTION', true),
        'mode' => env('XSS_MODE', 'block'),
    ],
    
    'cors' => [
        'enabled' => env('CORS_ENABLED', false),
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
        'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE')),
        'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization')),
    ],
];