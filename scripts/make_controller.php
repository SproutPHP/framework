<?php

$name = $argv[2];
$class = ucfirst($name);
$path = __DIR__ . "/../app/Controllers/{$class}.php";

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
