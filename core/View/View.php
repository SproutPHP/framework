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

        // Register global helper functions like assets() debug() if it exists as Twig doesn't have direct access to PHP global functions by default
        self::registerAllHelpers();
    }
    
    /**
     * Get All PHP Helpers that are currently defined
     */
    private static function discoverHelpers() {
        $functions = get_defined_functions();
        return $functions['user'];
    }

    /**
     * Register all helpers found in $helperFunctions
     */
    private static function registerAllHelpers(){
        $helperFunctions = self::discoverHelpers();

        foreach($helperFunctions as $functionName) {
            if(function_exists($functionName)) {
                self::$twig->addFunction(new TwigFunction($functionName, $functionName));
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