<?php

namespace Core\View;

use Core\Support\Debugbar;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    protected static $twig;

    public static function init()
    {
        $viewsPath = __DIR__ . '/../../app/Views';
        $loader = new FilesystemLoader($viewsPath);
        
        $twigConfig = config('view.twig', []);
        
        // Configure cache properly
        $cachePath = false; // Default to no cache
        if ($twigConfig['cache'] && $twigConfig['cache'] !== false) {
            // If cache is enabled, use a proper cache directory
            $cachePath = __DIR__ . '/../../storage/twig-cache';
            if (!is_dir($cachePath)) {
                mkdir($cachePath, 0777, true);
            }
        }
        
        self::$twig = new Environment($loader, [
            'cache' => $cachePath,
            'debug' => $twigConfig['debug'] ?? true,
            'auto_reload' => $twigConfig['auto_reload'] ?? true,
            'strict_variables' => $twigConfig['strict_variables'] ?? false,
        ]);

        // Register helpers for Twig: auto-register all from helpers.php, merge with config('view.twig_helpers') if set.
        self::registerExplicitHelpers();
    }
    
    /**
     * Register helpers for Twig: auto-register all from helpers.php, merge with config('view.twig_helpers') if set.
     */
    private static function registerExplicitHelpers() {
        // 1. Get all user-defined functions (auto-discover from helpers.php and any loaded helpers)
        $userFunctions = get_defined_functions()['user'];

        // 2. Get explicit list from config, if any
        $configHelpers = config('view.twig_helpers', []);

        // 3. Merge and deduplicate
        $allHelpers = array_unique(array_merge($userFunctions, $configHelpers));

        // 4. Register each helper if it exists
        foreach ($allHelpers as $helper) {
            if (function_exists($helper)) {
                self::$twig->addFunction(new \Twig\TwigFunction($helper, $helper));
            }
        }
    }

    public static function render($template, $data = [])
    {
        if (!self::$twig) {
            self::init();
        }

        // Check if this is an AJAX/HTMX request
        if (Debugbar::isAjaxRequest() && config('app.debug', false)) {
            // Reset debugbar for this request
            Debugbar::resetForRequest();
            
            // Render the template first
            $content = self::$twig->render($template . '.twig', $data);
            
            // Append debugbar to the response
            $debugbar = Debugbar::render();
            
            echo $content . $debugbar;
            return;
        }

        // Regular request handling
        if (config('app.debug', false)) {
            $data['debugbar'] = Debugbar::render();
            $data['app_debug'] = true;
        }

        echo self::$twig->render($template . '.twig', $data);
    }
}