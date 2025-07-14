<?php

namespace App\Middlewares;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $cors = config('security.cors', []);
        if (!($cors['enabled'] ?? false)) {
            return $next($request);
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
        $allowedOrigins = $cors['allowed_origins'] ?? ['*'];
        $allowOrigin = in_array('*', $allowedOrigins) ? '*' : (in_array($origin, $allowedOrigins) ? $origin : '');
        if ($allowOrigin) {
            header("Access-Control-Allow-Origin: $allowOrigin");
        }
        header('Vary: Origin');
        header('Access-Control-Allow-Methods: ' . implode(',', $cors['allowed_methods'] ?? ['GET','POST','PUT','DELETE']));
        header('Access-Control-Allow-Headers: ' . implode(',', $cors['allowed_headers'] ?? ['Content-Type','Authorization']));
        header('Access-Control-Allow-Credentials: true');

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        return $next($request);
    }
} 