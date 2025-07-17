<?php

namespace App\Middlewares;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class VerifyCsrfToken implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        // Check CSRF on state-changeing request
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            $sessionToken = $_SESSION['_csrf_token'] ?? null;

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                http_response_code(419);
                exit('CSRF token mismatch.');
            }
        }
        return $next($request);
    }
}
