<?php

namespace Core\Console\Commands;

class MakeRoute
{
    public static function handle($name)
    {
        $resource = $name ?? null;

        if (!$resource) {
            echo "❌ Please provide a resource name.\n";
            exit(1);
        }

        $controller = ucfirst($resource) . 'Controller';
        $routeFile = __DIR__ . '/../../../routes/web.php';
        $routeContents = file_get_contents($routeFile);

        /**
         * Check if routes exists
         */
        if (strpos($routeContents, "Resource routes for {$controller}") !== false) {
            echo "⚠️ Resource routes for '{$controller}' already exist in routes/web.php\n";
            exit(0);
        }

        $routeBlock = <<<PHP

        // 🌱 Resource routes for {$controller}
        Route::get('/{$resource}', '{$controller}@index');
        Route::get('/{$resource}/create', '{$controller}@create');
        Route::post('/{$resource}', '{$controller}@store');
        Route::get('/{$resource}/{id}', '{$controller}@show');
        Route::get('/{$resource}/{id}/edit', '{$controller}@edit');
        Route::put('/{$resource}/{id}', '{$controller}@update');
        Route::delete('/{$resource}/{id}', '{$controller}@destroy');
        PHP;

        file_put_contents($routeFile, $routeBlock . PHP_EOL, FILE_APPEND);
        echo "✅ Resource routes added to routes/web.php for '{$controller}'\n";
    }
}
