<?php

namespace Core\Console\Commands;

class MakeController
{
    public static function handle($name)
    {
        $class = ucfirst($name);
        $dir = __DIR__ . "/../../../app/Controllers/";
        $path = "{$dir}/{$class}.php";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($path)) {
            echo "❌ Controller already exists: {$class}.php\n";
            exit(1);
        }

        $template = <<<PHP
        <?php

        namespace App\Controllers;

        class {$class}
        {
            public function index()
            {
                return view('{$name}.index', ['title' => '{$class} Index']);
            }
        }
        PHP;

        file_put_contents($path, $template);
        echo "✅ Controller created: app/Controllers/{$class}.php\n";
    }
}
