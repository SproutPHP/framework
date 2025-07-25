<?php

namespace Core\Error;

class ErrorHandler
{
    public static function register()
    {
        // Skip registering handlers in CLI or PHPUnit (for testing)
        if (php_sapi_name() === 'cli' || getenv('PHPUNIT_RUNNING') || defined('PHPUNIT_COMPOSER_INSTALL')) {
            return;
        }
        ini_set('display_errors', 0); // Prevent raw output
        ini_set('log_errors', 1);
        error_reporting(E_ALL);

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatal']);
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        self::render("Error [$errno]: $errstr", $errfile, $errline);
        return true; // prevent default handler
    }

    public static function handleException($exception)
    {
        self::render("Uncaught Exception: " . $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
    }

    public static function handleFatal()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_PARSE, E_COMPILE_ERROR])) {
            self::render("Fatal Error: {$error['message']}", $error['file'], $error['line']);
        }
    }

    public static function render($message, $file, $line, $trace = null, $code = 500)
    {
        if (config('app.env', 'local') === 'local' && config('app.debug', false)) {
            echo "<div style='padding:1.5rem; font-family:monospace; background:#fff3f3; border:1px solid #ffb3b3; color:#b30000;'>";
            echo "<h2>🍂 SproutPHP Error</h2>";
            echo "<strong>Message:</strong> $message<br>";
            echo "<strong>File:</strong> $file<br>";
            echo "<strong>Line:</strong> $line<br>";

            if ($trace) {
                echo "<details style='margin-top:1rem;'><summary>Stack trace</summary><pre>$trace</pre></details>";
            }

            echo "</div>";
        } else {
            http_response_code($code);

            $errorId = null;
            if ($code >= 500) {
                $errorId = log_error("[{$code}] $message in $file:$line");
            }

            $viewFile = file_exists(__DIR__ . "/../app/Views/errors/{$code}.twig")
                ? "errors/{$code}"
                : "errors/error"; // fallback

            try {
                echo \Core\View\View::render($viewFile, ['error_id' => $errorId]);
            } catch (\Throwable $ex) {
                echo "<div style='padding:1.5rem; font-family:monospace; background:#fff3f3; border:1px solid #ffb3b3; color:#b30000;'>";
                echo "<h2>SproutPHP Error</h2>";
                echo "<strong>Sorry, an error occurred and no error view could be loaded.</strong><br>";
                echo "Error code: $code<br>";
                if ($errorId) {
                    echo "Error ID: $errorId<br>";
                }
                echo "</div>";
            }
        }

        // Only call exit for web requests, not for CLI
        if (php_sapi_name() !== 'cli' && php_sapi_name() !== 'phpdbg') {
            exit;
        }
    }
}
