<?php

namespace App\Middlewares;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class XssProtection implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $response = $next($request);

        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none';");
        
        return $response;
    }
}