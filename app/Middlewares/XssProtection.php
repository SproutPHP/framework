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
        
        // Set relaxed ContentSecurityPolicy in development, strict in production
        if ((function_exists('env') && env('APP_ENV') === 'local') || (function_exists('env') && env('APP_DEBUG') === 'true')) {
            header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' https://img.shields.io; object-src 'none';");
        } else {
            // Production: strict ContentSecurityPolicy
            header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none';");
        }
        
        return $response;
    }
}