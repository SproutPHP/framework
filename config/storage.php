<?php

return [
    'default' => env('STORAGE_DISK', 'public'),
    'disks' => [
        'public' => [
            'root' => env('PUBLIC_STORAGE_PATH', 'storage/app/public'),
            'url' => env('STORAGE_PUBLIC_LINK', '/storage'),
            'visibility' => 'public',
        ],
        'private' => [
            'root' => env('STORAGE_PRIVATE_ROOT', 'storage/app/private'),
            'visibility' => 'private',
        ]
    ],
];