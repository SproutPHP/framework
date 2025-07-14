<?php

namespace App\Middlewares;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class XssProtection implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        // Set headers BEFORE calling $next
        $xssEnabled = config('security.xss.enabled', true);
        $xssMode = config('security.xss.mode', 'block');
        if ($xssEnabled) {
            header("X-XSS-Protection: 1; mode={$xssMode}");
        }
        header("X-Content-Type-Options: nosniff");

        $cspEnabled = config('security.csp.enabled', true);
        if ($cspEnabled) {
            $cspPolicy = $this->getCspPolicy();
            header("Content-Security-Policy: {$cspPolicy}");
        }

        $response = $next($request);
        return $response;
    }
    
    private function getCspPolicy(): string
    {
        $env = config('app.env', 'local');
        $debug = config('app.debug', false);
        
        // Base CSP policy
        $basePolicy = "default-src 'self'; script-src 'self'; object-src 'none';";
        
        if ($env === 'local' || $debug) {
            // Development: relaxed policy
            return $basePolicy . " style-src 'self' 'unsafe-inline'; img-src 'self' https://img.shields.io;";
        } else {
            // Production: strict policy
            return $basePolicy . " style-src 'self'; img-src 'self';";
        }
    }
}