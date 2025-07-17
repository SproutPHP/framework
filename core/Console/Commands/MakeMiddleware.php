<?php

namespace Core\Console\Commands;

class MakeMiddleware
{
    public static function handle($name)
    {
        $className = ucfirst($name);
        $namespace = 'App\\Middlewares';
        $dir = __DIR__ . '/../../../app/Middlewares';
        $file = "$dir/{$className}.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (file_exists($file)) {
            echo "❌ Middleware already exists: $file\n";
            return;
        }

        $content = <<<PHP
                    <?php

                    namespace $namespace;

                    use Core\Http\Middleware\MiddlewareInterface;
                    use Core\Http\Request;

                    class $className implements MiddlewareInterface
                    {
                        public function handle(Request \$request, callable \$next)
                        {
                            // Your middleware logic here
                            return \$next(\$request);
                        }
                    }
                    PHP;

        file_put_contents($file, $content);
        echo "✅ Middleware created: app/Middlewares/{$className}.php\n";
    }
} 