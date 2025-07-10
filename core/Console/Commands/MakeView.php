<?php

namespace Core\Console\Commands;

class MakeView
{
    public static function handle($name)
    {
        $viewDir = __DIR__ . '/../../../app/Views';
        $path = "$viewDir/{$name}.twig";

        if (!is_dir($viewDir)) {
            mkdir($viewDir, 0777, true);
        }

        if (file_exists($path)) {
            echo "❌ View already exists: {$name}.twig\n";
            exit(1);
        }

        $template = <<<TWIG
            {% extends "layouts/base.twig" %}
            {% block title %}{{ title }}{% endblock %}

            {% block content %}
            <h1>{{ title }}</h1>
            <p>This is the {{ title }} page.</p>
            {% endblock %}
            TWIG;

        file_put_contents($path, $template);
        echo "✅ View created: app/Views/{$name}.twig\n";
    }
}
