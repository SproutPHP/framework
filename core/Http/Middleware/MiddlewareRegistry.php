<?php

namespace Core\Http\Middleware;

class MiddlewareRegistry
{
    public static $map = [
        'csrf' => \App\Middlewares\VerifyCsrfToken::class,
        'xss'  => \App\Middlewares\XssProtection::class,
        'route-test-mw' => \App\Middlewares\RouteTestMiddleware::class,
        // Register your middlewares here
    ];
}