<?php

namespace Core\Routing;

use Core\Http\Middleware\MiddlewareKernel;
use Core\Http\Request;

class Router
{
    protected $routes = [];

    public  function registerRoute($route)
    {
        $this->routes[$route->method][$route->uri] = $route;
    }

    public function get($uri, $callback)
    {
        return Route::get($uri, $callback);
    }

    public function post($uri, $callback)
    {
        return Route::post($uri, $callback);
    }

    public function put($uri, $callback)
    {
        return Route::put($uri, $callback);
    }

    public function patch($uri, $callback)
    {
        return Route::patch($uri, $callback);
    }

    public function delete($uri, $callback)
    {
        return Route::delete($uri, $callback);
    }

    /**
     * Matches the request to the route and runs the callback if it exists
     */
    public function dispatch(Request $request)
    {
        $method = $request->method;
        $uri = $request->uri;

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            if (env('APP_ENV') === 'local') {
                echo "<div style='padding:1.5rem; font-family:monospace; background:#fff3f3; border:1px solid #ffb3b3; color:#b30000;'>";
                echo "<h2>404: Route not found</h2>";
                echo "</div>";
            } else {
                echo \Core\View\View::render('errors/404');
            }
            exit;
        }

        $route = $this->routes[$method][$uri];

        /**
         * Collecting middleware Global and Route-specific
         */
        $routeMiddleware = $route->middleware ?? [];

        $kernel = new MiddlewareKernel($routeMiddleware);

        $coreHandler = function ($request) use ($route) {
            $callback = $route->action;

            /**
             * If it is a closure
             */
            if (is_callable($callback)) {
                return $callback();
            }

            /**
             * If it is a string like 'SiteController@index'
             */
            if (is_string($callback)) {
                list($controllerName, $methodName) = explode('@', $callback);

                $controllerClass = 'App\\Controllers\\' . $controllerName;

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass;

                    if (method_exists($controller, $methodName)) {
                        return $controller->$methodName();
                    }
                }

                http_response_code(500);
                return "Controller or method not found";
            }
        };
        return $kernel->handle($request, $coreHandler);
    }
}
