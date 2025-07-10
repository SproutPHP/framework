<?php

namespace Core\Console;

class PostInstall
{
    protected static function download($url, $savePath)
    {
        echo "📦 Downloading $url...\n";
        $data = @file_get_contents($url);

        if ($data === false) {
            echo "❌ Failed to download: $url\n";
            return;
        }

        @mkdir(dirname($savePath), 0777, true);
        file_put_contents($savePath, $data);
        echo "✅ Saved to $savePath\n";
    }

    public static function run()
    {
        // HTMX
        self::download(
            'https://unpkg.com/htmx.org@latest/dist/htmx.min.js',
            __DIR__ . '/../../public/assets/js/sprout.min.js'
        );

        // Pico.css
        self::download(
            'https://unpkg.com/@picocss/pico@latest/css/pico.min.css',
            __DIR__ . '/../../public/assets/css/sprout.min.css'
        );
    }
}
