<?php

namespace Core\Routing;

class Route
{
    public static $router;
    public $uri;
    public $method;
    public $action;
    public $middleware = [];

    public function __construct($method, $uri, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }

    public static function get($uri, $action)
    {
        $route = new self('GET', $uri, $action);
        self::$router->registerRoute($route);
        return $route;
    }

    public static function post($uri, $action)
    {
        $route = new self('POST', $uri, $action);
        self::$router->registerRoute($route);
        return $route;
    }

    public static function put($uri, $action)
    {
        $route = new self('PUT', $uri, $action);
        self::$router->registerRoute($route);
        return $route;
    }

    public static function patch($uri, $action)
    {
        $route = new self('PATCH', $uri, $action);
        self::$router->registerRoute($route);
        return $route;
    }

    public static function delete($uri, $action)
    {
        $route = new self('DELETE', $uri, $action);
        self::$router->registerRoute($route);
        return $route;
    }

    public function middleware($middleware)
    {
        $this->middleware = is_array($middleware) ? $middleware : [$middleware];
        return $this;
    }
}
