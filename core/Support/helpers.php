<?php

use Core\Support\Validator;
use Core\View\View;

/**
 * view helper accepting template-name and data
 */
if (!function_exists('view')) {
    function view($template, $data = [], $return = false)
    {
        $output = View::render($template, $data, true); // always get as string
        if ($return) {
            return $output;
        }
        echo $output;
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
                if ($line === '' || $line[0] === '#')
                    continue;

                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v);
            }
        }

        $value = $env[$key] ?? $default;
        // Cast boolean-like strings to real booleans
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true')
                return true;
            if ($lower === 'false')
                return false;
            if ($lower === '1')
                return true;
            if ($lower === '0')
                return false;
        }
        return $value;
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
        $repo = config('app.repo', 'SproutPHP/framework');
        $userAgent = config('app.user_agent', 'sproutphp-app');
        $token = env('GITHUB_TOKEN');
        $url = "https://api.github.com/repos/$repo/releases";
        $headers = "User-Agent: $userAgent\r\n";
        if ($token) {
            $headers .= "Authorization: token $token\r\n";
        }
        $opts = [
            "http" => [
                "header" => $headers
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

/**
 * CSRF Token
 */
if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        $token = $_SESSION['_csrf_token'];
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}

/**
 * CSRF Token (string only)
 */
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        return $_SESSION['_token'] ?? '';
    }
}

/**
 * Config Helper
 */
if (!function_exists('config')) {
    function config($key, $default = null)
    {
        static $configs = [];

        $segments = explode('.', $key);
        $file = $segments[0];

        if (!isset($configs[$file])) {
            $configPath = __DIR__ . '/../../config/' . $file . '.php';
            if (file_exists($configPath)) {
                $configs[$file] = require $configPath;
            } else {
                return $default;
            }
        }

        $value = $configs[$file];
        array_shift($segments); // Remove the file name from segments

        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }
}

if (!function_exists('is_htmx_request')) {
    /**
     * Returns true if the request is an HTMX request.
     */
    function is_htmx_request()
    {
        return isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';
    }
}

if (!function_exists('render_fragment_or_full')) {
    /**
     * Renders a fragment for HTMX/AJAX, or wraps it in a layout for normal requests.
     * @param string $fragmentView The Twig view for the fragment (e.g., 'partials/security-test')
     * @param array $data Data to pass to the view
     * @param string $layoutView The layout view (default: 'base')
     * @param string $blockName The block name in the layout to inject the fragment (default: 'content')
     */
    function render_fragment_or_full($fragmentView, $data = [], $layoutView = 'layouts/base', $blockName = 'content')
    {
        $isPartial = is_htmx_request() || is_ajax_request();
        if ($isPartial) {
            echo view($fragmentView, $data, true);
        } else {
            $data[$blockName] = view($fragmentView, $data, true); // get fragment as string
            echo view($layoutView, $data, true); // render layout as string, then echo
        }
    }
}

/**
 * Validator Helper
 */
function validate($data, $rules)
{
    return new Validator($data, $rules);
}