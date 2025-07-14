<?php

return [
    'default' => env('MAIL_DRIVER', 'smtp'),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@sproutphp.com'),
        'name' => env('MAIL_FROM_NAME', 'SproutPHP'),
    ],
    'drivers' => [
        'smtp' => [
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        ],

        'sendmail' => [
            'path'=>'/usr/sbin/sendmail -bs',
        ]
    ],
];
