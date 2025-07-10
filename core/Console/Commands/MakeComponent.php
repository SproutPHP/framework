<?php

namespace Core\Console\Commands;

class MakeComponent
{
    public static function handle($name)
    {
        $name = strtolower(trim($name));
        $file = __DIR__ . '/../../../app/Views/Components/' . $name . '.twig';

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
