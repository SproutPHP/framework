<?php

namespace App\Middlewares;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class RouteTestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $response = $next($request);
        return $response . "\n<!-- RouteTestMiddleware was here! -->";
    }
} 