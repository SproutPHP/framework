<?php

namespace Core\Console\Commands;

class MakeView
{
    public static function handle($name, $flags = [])
    {
        $viewDir = __DIR__ . '/../../../app/Views';
        $path = "$viewDir/{$name}.twig";
        $htmx = in_array('--htmx', $flags);

        if (!is_dir($viewDir)) {
            mkdir($viewDir, 0777, true);
        }

        if (file_exists($path)) {
            echo "❌ View already exists: {$name}.twig\n";
            exit(1);
        }

        $quote = self::fetchRandomQuote();
        $template = $htmx ?
            <<<TWIG
            {% extends 'layouts/base.twig' %}

            {% block content %}
                <h2>HTMX Demo Page</h2>

                <button hx-get="/" hx-target="#demo" hx-swap="innerHTML">
                    Load Partial via HTMX
                </button>

                <div id="demo" style="margin-top:1rem;">
                    <em>Click the button above to load content.</em>
                </div>
            {% endblock %}
            TWIG
            : <<<TWIG
            {% extends "layouts/base.twig" %}
            {% block title %}{{ title }}{% endblock %}

            {% block content %}
            <h1>{{ title }}</h1>
            <p>This is the <strong>{{ title }}</strong> page.</p>
            <blockquote style="margin-top:2rem; font-style:italic; color:#888;">
                {$quote}
            </blockquote>
            {% endblock %}
            TWIG;

        file_put_contents($path, $template);
        echo "✅ View created: app/Views/{$name}.twig\n";

        if ($htmx) {
            self::addDemoRoute($name);
        }
    }

    protected static function fetchRandomQuote()
    {
        $url = 'https://dummyjson.com/quotes/random';
        $opts = [
            "http" => [
                "header" => "User-Agent: sproutphp-cli\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $json = @file_get_contents($url, false, $context);
        $data = json_decode($json, true);
        if (isset($data['quote'])) {
            return $data['quote'] . (isset($data['author']) ? " — " . $data['author'] : "");
        }
        return "“Code never lies, comments sometimes do.” — Ron Jeffries";
    }

    protected static function addDemoRoute($name)
    {
        $routesFile = __DIR__ . '/../../../routes/web.php';
        $route = <<<PHP
        \n// Auto-generated HTMX demo route:
        Route::get('/{$name}/load', function(){
            return view('{$name}', ['title' => '{$name} Page']);
        });
        PHP;

        if (strpos(file_get_contents($routesFile), "/{$name}/load") === false) {
            file_put_contents($routesFile, $route, FILE_APPEND);
            echo "✅ Demo HTMX route added: /{$name}/load\n";
        } else {
            echo "⚠️ Route /{$name}/load already exists.\n";
        }
    }
}
