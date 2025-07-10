<?php

namespace Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    protected static $twig;

    public static function init()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../app/Views');
        self::$twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);

        // Register global functions like assets() debug() if it exists as Twig doesn’t have direct access to PHP global functions by default
        if (function_exists('assets')) {
            self::$twig->addFunction(new TwigFunction('assets', 'assets'));
        }

        if (function_exists('debug') || function_exists('dd')) {
            self::$twig->addFunction(new \Twig\TwigFunction('debug', 'debug'));
            self::$twig->addFunction(new \Twig\TwigFunction('dd', 'dd'));
        }
    }

    public static function render($template, $data = [])
    {
        if (!self::$twig) {
            self::init();
        }

        echo self::$twig->render($template . '.twig', $data);
    }
}
