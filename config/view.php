<?php

return [
    'engine' => env('VIEW_ENGINE', 'twig'),

    'twig' => [
        'cache' => env('TWIG_CACHE', false),
        'debug' => env('TWIG_DEBUG', true),
        'auto_reload' => env('TWIG_AUTO_RELOAD', true),
        'strict_variables' => env('TWIG_STRICT_VARIABLES', false),
    ],

    'paths' => [
        'views' => env('VIEW_PATH', '/app/Views'),
        'components' => env('COMPONENT_PATH', '/app/Views/components'),
        'layouts' => env('LAYOUT_PATH', '/app/Views/layouts'),
    ],
];
