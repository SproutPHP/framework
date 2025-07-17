<?php

namespace Core\Routing;

use Core\Http\Middleware\MiddlewareKernel;
use Core\Http\Middleware\MiddlewareRegistry;
use Core\Http\Request;

class Router
{
    protected $routes = [];

    public function registerRoute($route)
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

        // Try exact match first
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            return $this->runRoute($route, $request);
        }

        // Try pattern match for dynamic parameters
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $pattern => $route) {
                $paramNames = [];
                $regex = preg_replace_callback('/\{([a-zA-Z0-9_]+)(:([^}]+))?\}/', function ($matches) use (&$paramNames) {
                    $paramNames[] = $matches[1];
                    if (isset($matches[3])) {
                        return '(' . $matches[3] . ')'; // custom regex
                    }
                    return '([^/]+)'; // default: match anything except /
                }, $pattern);
                $regex = '#^' . $regex . '$#';
                if (preg_match($regex, $uri, $matches)) {
                    array_shift($matches); // remove full match
                    $params = array_combine($paramNames, $matches);
                    return $this->runRoute($route, $request, $params);
                }
            }
        }

        // No match found
        http_response_code(404);
        if (config('app.env', 'local') === 'local') {
            echo "<div style='padding:1.5rem; font-family:monospace; background:#fff3f3; border:1px solid #ffb3b3; color:#b30000;'>";
            echo "<h2>404: Route not found</h2>";
            echo "</div>";
        } else {
            echo \Core\View\View::render('errors/404');
        }
        exit;
    }

    /**
     * Run the matched route with parameters
     */
    protected function runRoute($route, $request, $params = [])
    {
        /**
         * Collecting middleware Route-specific
         */
        $routeMiddleware = $route->middleware ?? [];

        // Resolve the alias to classNames
        $resolvedMiddleware = [];
        foreach ($routeMiddleware as $mw) {
            if (class_exists($mw)) {
                $resolvedMiddleware[] = $mw;
            } elseif (isset(MiddlewareRegistry::$map[$mw])) {
                $resolvedMiddleware[] = MiddlewareRegistry::$map[$mw];
            } else {
                $msg = "[SproutPHP] Middleware alias '$mw' is not registerd. Please check your routes or MiddlewareRegistry.";
                if (function_exists('log_error')) {
                    log_error($msg);
                }

                throw new \Exception("$msg");
            }
        }

        $kernel = new MiddlewareKernel($resolvedMiddleware);

        $coreHandler = function ($request) use ($route, $params) {
            $callback = $route->action;

            /**
             * If it is a closure
             */
            if (is_callable($callback)) {
                return call_user_func_array($callback, $params);
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
                        return call_user_func_array([$controller, $methodName], $params);
                    }
                }

                http_response_code(500);
                return "Controller or method not found";
            }
        };
        return $kernel->handle($request, $coreHandler);
    }
}
