<?php

namespace Core\Console\Commands;

class MakeModel
{
    public static function handle($name)
    {
        $class = ucfirst($name);
        $dir = __DIR__ . "/../../../app/Models";
        $path = "{$dir}/{$class}.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($path)) {
            echo "❌ Model already exists: {$class}.php\n";
            exit(1);
        }

        $template = <<<PHP
                    <?php

                    namespace App\Models;

                    class {$class}
                    {
                        // Add your properties and methods
                    }
                    PHP;

        file_put_contents($path, $template);
        echo "✅ Model created: app/Models/{$class}.php\n";
    }
}
