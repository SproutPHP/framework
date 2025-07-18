<?php

namespace Core\Console\Commands;

class EnvCheck
{
    public static function handle($args = [])
    {
        // Colors
        $green = "\033[32m";
        $red = "\033[31m";
        $yellow = "\033[33m";
        $cyan = "\033[36m";
        $reset = "\033[0m";
        $bold = "\033[1m";

        // App info
        $env = config('app.env', 'local');
        $debug = config('app.debug', false) ? 'enabled' : 'disabled';
        $version = getLatestRelease();
        $sessionConfig = config('app.session', []);
        $sessionPath = $sessionConfig['path'] ?? '/storage/sessions';
        $baseDir = realpath(__DIR__ . '/../../..');
        $sessionDir = $baseDir . $sessionPath;

        // Output
        echo "{$bold}{$cyan}SproutPHP Environment Check{$reset}\n";
        echo "---------------------------\n";
        echo "Environment: {$yellow}{$env}{$reset}\n";
        echo "Debug: {$yellow}{$debug}{$reset}\n";
        echo "Sprout Version: {$yellow}{$version}{$reset}\n";
        echo "\n";
        echo "Session Directory: {$yellow}{$sessionDir}{$reset}\n";

        if (!is_dir($sessionDir)) {
            echo "{$red}✗ Session directory does not exist!{$reset}\n";
        } elseif (!is_writable($sessionDir)) {
            echo "{$red}✗ Session directory is not writable!{$reset}\n";
        } else {
            echo "{$green}✓ Session directory is writable.{$reset}\n";
        }

        echo "\n";
        // Add more checks as needed
        echo "For more info, see config/app.php and documentation.\n";
    }
}