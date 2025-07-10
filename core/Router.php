<?php

namespace Core;

class Router
{
    protected $routes = [];

    /**
     * Registers a GET & POST route with URI and Callback
     */
    public function get($uri, $callback)
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post($uri, $callback)
    {
        $this->routes['POST'][$uri] = $callback;
    }

    public function put($uri, $callback)
    {
        $this->routes['PUT'][$uri] = $callback;
    }

    public function patch($uri, $callback)
    {
        $this->routes['PATCH'][$uri] = $callback;
    }

    public function delete($uri, $callback)
    {
        $this->routes['DELETE'][$uri] = $callback;
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
                echo \Core\View::render('errors/404');
            }
            exit;
        }

        $callback = $this->routes[$method][$uri];

        /**
         * If it is a closure
         */
        if (is_callable($callback)) {
            echo $callback();
            return;
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
                    echo $controller->$methodName();
                    return;
                }
            }

            http_response_code(500);
            echo "Controller or method not found";
        }
    }
}
