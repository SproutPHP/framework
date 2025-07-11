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
        $loader = new FilesystemLoader(__DIR__ . '/../../app/Views');
        self::$twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
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
        if (env('APP_DEBUG') === 'true') {
            $data['debugbar'] = Debugbar::render();
            $data['app_debug'] = true;
        }
        
        if (!self::$twig) {
            self::init();
        }

        echo self::$twig->render($template . '.twig', $data);
    }
}