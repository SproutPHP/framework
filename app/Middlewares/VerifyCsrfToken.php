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

            // If not found and JSON request, try to extract from JSON body
            if (!$token && isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                if (is_array($data) && isset($data['_csrf_token'])) {
                    $token = $data['_csrf_token'];
                }
            }

            $sessionToken = $_SESSION['_csrf_token'] ?? null;

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                http_response_code(419);
                exit('CSRF token mismatch.');
            }
        }
        return $next($request);
    }
}
