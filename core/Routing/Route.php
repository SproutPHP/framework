<?php

namespace Core\Routing;

class Route
{
    public static $router;

    public static function get($uri, $action)
    {
        self::$router->get($uri, $action);
    }

    public static function post($uri, $action)
    {
        self::$router->post($uri, $action);
    }

    public static function put($uri, $action)
    {
        self::$router->put($uri, $action);
    }

    public static function patch($uri, $action)
    {
        self::$router->patch($uri, $action);
    }

    public static function delete($uri, $action)
    {
        self::$router->delete($uri, $action);
    }
}
