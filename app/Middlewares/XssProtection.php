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
        $basePolicy = "default-src 'self'; object-src 'none';";

        // Allow developers to specify connect-src for external APIs via .env (CSP_CONNECT_SRC)
        $connectSrcDomains = config('security.csp.connect_src', []);
        $connectSrc = '';
        if (!empty($connectSrcDomains)) {
            $connectSrc = " connect-src 'self'";
            foreach ($connectSrcDomains as $domain) {
                $connectSrc .= ' ' . $domain;
            }
            $connectSrc .= ';';
        }

        // Allow developers to specify img-src for external images via .env (CSP_IMG_SRC)
        $imgSrcDomains = config('security.csp.img_src', []);
        $imgSrc = '';
        if (!empty($imgSrcDomains)) {
            $imgSrc = " img-src 'self'";
            foreach ($imgSrcDomains as $domain) {
                $imgSrc .= ' ' . $domain;
            }
            $imgSrc .= ';';
        }

        if ($env === 'local' || $debug) {
            // Development: relaxed policy
            return $basePolicy . " script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';" . $imgSrc . $connectSrc;
        } else {
            // Production: strict policy
            if ($debug) {
                // Optionally log a warning if debug is on in production
                if (function_exists('log_error')) {
                    log_error("[SproutPHP] WARNING: app.debug is true in production! CSP is strict, but debug mode should be off.");
                }
            }
            return $basePolicy . " script-src 'self'; style-src 'self';" . $imgSrc . $connectSrc;
        }
    }
}