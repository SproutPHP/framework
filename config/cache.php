<?php

return [
    'default' => env('CACHE_DRIVER', 'file'),
    
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => env('CACHE_PATH', '/storage/cache'),
        ],
        
        'redis' => [
            'driver' => 'redis',
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'password' => env('REDIS_PASSWORD'),
            'database' => env('REDIS_DB', 0),
        ],
    ],
    
    'prefix' => env('CACHE_PREFIX', 'sprout_'),
];