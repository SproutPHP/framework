<?php

namespace Core\Console\Commands;

class MakeComponent
{
    public static function handle($name)
    {
        $name = strtolower(trim($name));
        $dir = __DIR__ . '/../../../app/Views/Components/';
        $file = "{$dir}/{$name}.twig";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($file)) {
            echo "⚠️ Component '{$name}' already exists.\n";
            return;
        }

        $template = <<<TWIG
                {# SproutPHP Component: {$name}.twig #}
                <div class="component {$name} {{ type|default('info') }}">
                    <!-- Component HTML -->
                    {{ message|default('This is a component.') }}
                    <!--
                        Usage:
                        {% include 'components/{$name}.twig' with { message: '{$name} created!', type: 'success' } %}
                    -->
                </div>
                TWIG;

        file_put_contents($file, $template);
        echo "✅ Component created: app/Components/{$name}.twig\n";
    }
}
