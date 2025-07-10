<?php

$resource = $argv[2] ?? null;

if (!$resource) {
    echo "❌ Please provide a resource name.\n";
    exit(1);
}

$class = ucfirst($resource);
$controllerPath = __DIR__ . "/../app/Controllers/{$class}Controller.php";
$modelPath = __DIR__ . "/../app/Models/{$class}.php";
$viewsDir = __DIR__ . "/../app/Views/{$resource}";
$viewFile = $viewsDir . "/index.twig";

/**
 * Generating Controller
 */
if (!file_exists($controllerPath)) {
    $controllerTemplate = <<<PHP
<?php

namespace App\Controllers;

class {$class}Controller
{
    /**
    * Index view returning base template
    */
    public function index()
    {
        return view('{$resource}/index', ['title' => '{$class} Index']);
    }

    /**
    * Create Method
    */
    public function create() {}
    
    /**
    * Store Method
    */
    public function store() {}
    
    /**
    * Display Method
    * Parameter required @integer
    */
    public function show(\$id) {}
        
    /**
    * Edit Method
    * Parameter required @integer
    */
    public function edit(\$id) {}
    
    /**
    * Update Method
    * Parameter required @integer
    */
    public function update(\$id) {}
    
    /**
    * Destroy Method
    * Parameter required @integer
    */
    public function destroy(\$id) {}
}
PHP;

    file_put_contents($controllerPath, $controllerTemplate);
    echo "✅ Controller created: {$class}Controller\n";
} else {
    echo "⚠️ Controller already exists: {$class}Controller\n";
}

/**
 * Generating Model
 */
if (!file_exists($modelPath)) {
    $modelTemplate = <<<PHP
<?php

namespace App\Models;

class {$class}
{
    // Define properties and methods for {$class} model
}
PHP;

    file_put_contents($modelPath, $modelTemplate);
    echo "✅ Model created: {$class}\n";
} else {
    echo "⚠️ Model already exists: {$class}\n";
}

/**
 * Generating View + index.twig
 */
if (!is_dir($viewsDir)) {
    mkdir($viewsDir, 0777, true);
}

if (!file_exists($viewFile)) {
    $viewTemplate = <<<TWIG
{% extends "layouts/base.twig" %}

{% block title %}{{ title }}{% endblock %}

{% block content %}
  <h1>{{ title }}</h1>
  <p>This is the index page for <strong>{$class}</strong> resource.</p>
{% endblock %}
TWIG;

    file_put_contents($viewFile, $viewTemplate);
    echo "✅ View created: Views/{$resource}/index.twig\n";
} else {
    echo "⚠️ View already exists: Views/{$resource}/index.twig\n";
}

echo "✅ Resource '{$resource}' created.\n";

/**
 *  Auto-generate routes if not already present
 */
echo "📌 Registering routes...\n";
require __DIR__ . '/make_route.php';

