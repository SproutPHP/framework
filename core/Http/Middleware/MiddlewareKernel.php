<?php

namespace Core\Http\Middleware;

use Core\Http\Request;

class MiddlewareKernel
{
    protected array $middlewares = [];

    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    public function handle(Request $request, callable $coreHandler)
    {
        $handler = array_reduce(
            array_reverse($this->middlewares),
            fn($next, $middleware) => fn($req) => (new $middleware())->handle($req, $next),
            $coreHandler
        );

        return $handler($request);
    }
}
