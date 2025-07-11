<?php

use Core\View\View;

/**
 * view helper accepting template-name and data
 */
if (!function_exists('view')) {
    function view($template, $data = [])
    {
        return View::render($template, $data);
    }
}

/**
 * asset helper accepting assets path
 */
if (!function_exists('assets')) {
    function assets(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

/**
 * debug helper cleanly dumping variables accepting variable
 */
if (!function_exists('debug')) {
    function debug($var)
    {
        echo "<pre style='background:#f4f4f4; padding:1rem; border-radius:6px; font-size:0.9rem; overflow-x:auto;'>";
        var_dump($var);
        echo "</pre>";
    }
}

/**
 * dd helper cleanly dumping variable and die script
 */
if (!function_exists('dd')) {
    function dd($var)
    {
        debug($var);
        die;
    }
}

/**
 * env helper to setup different environments
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        static $env = [];

        if (empty($env)) {
            $file = __DIR__ . '/../../.env';
            if (!file_exists($file)) {
                return $default;
            }

            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;

                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v);
            }
        }

        return $env[$key] ?? $default;
    }
}

/**
 * log_error helper to logs to a file
 */
if (!function_exists('log_error')) {
    function log_error(string $message): string
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $id = uniqid('err_', true);
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] [ID: $id] $message" . PHP_EOL;

        file_put_contents("$logDir/error.log", $entry, FILE_APPEND);

        return $id;
    }
}

/**
 * error helper to trigger custom errors
 */
if (!function_exists('abort')) {
    function abort(int $code, string $message = '')
    {
        $message = $message ?: "HTTP Error $code";
        \Core\Error\ErrorHandler::render($message, __FILE__, __LINE__, null, $code);
    }
}

/**
 * Get the latest App Release
 */
if (!function_exists('getLatestRelease')) {
    function getLatestRelease()
    {
        $repo = env('SPROUT_REPO', 'SproutPHP/framework');
        $userAgent = env('SPROUT_USER_AGENT', 'sproutphp-app');
        $url = "https://api.github.com/repos/$repo/releases";
        $opts = [
            "http" => [
                "header" => "User-Agent: $userAgent\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $json = @file_get_contents($url, false, $context);
        $data = json_decode($json, true);

        if (is_array($data) && count($data) > 0) {
            $tag = $data[0]['tag_name'] ?? 'unknown';
            $isPrerelease = $data[0]['prerelease'] ? ' (pre-release)' : '';
            return $tag . $isPrerelease;
        }
        return 'unknown';
    }
}

/**
 * Check if current request is AJAX or HTMX
 */
if (!function_exists('is_ajax_request')) {
    function is_ajax_request(): bool
    {
        return (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (
            isset($_SERVER['HTTP_HX_REQUEST']) && 
            $_SERVER['HTTP_HX_REQUEST'] === 'true'
        );
    }
}
