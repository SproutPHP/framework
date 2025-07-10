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

        $quote = self::fetchRandomQuote();
        $template = <<<TWIG
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
    }

    static function fetchRandomQuote()
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
}
