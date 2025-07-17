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
            function($next, $middleware) {
                return function($req) use ($next, $middleware) {
                    // Validate middleware class
                    if (!is_string($middleware) || !class_exists($middleware)) {
                        throw new \Exception("Invalid middleware class: " . (is_string($middleware) ? $middleware : gettype($middleware)));
                    }
                    
                    // Check if middleware implements the interface
                    if (!is_subclass_of($middleware, MiddlewareInterface::class)) {
                        throw new \Exception("Middleware class '$middleware' must implement MiddlewareInterface");
                    }
                    
                    return (new $middleware())->handle($req, $next);
                };
            },
            $coreHandler
        );

        return $handler($request);
    }
}
