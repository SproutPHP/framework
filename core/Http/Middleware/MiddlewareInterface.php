<?php

namespace Core\Http\Middleware;

use Core\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next);
}