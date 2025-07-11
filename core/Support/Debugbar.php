<?php

namespace Core\Support;

use Core\Database\DB;

class Debugbar
{
    /**
     * Check if current request is AJAX or HTMX
     */
    public static function isAjaxRequest(): bool
    {
        return (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) || (
            isset($_SERVER['HTTP_HX_REQUEST']) && 
            $_SERVER['HTTP_HX_REQUEST'] === 'true'
        );
    }

    /**
     * Reset debugbar for new request
     */
    public static function resetForRequest()
    {
        // Reset query log for this specific request
        DB::resetQueryLog();
        
        // Set new start time for this request
        if (!defined('REQUEST_START')) {
            define('REQUEST_START', microtime(true));
        }
    }

    public static function render()
    {
        if (env('APP_DEBUG') !== 'true') return;

        $endTime = microtime(true);
        $startTime = defined('REQUEST_START') ? REQUEST_START : SPROUT_START;
        $executionTime = round(($endTime - $startTime) * 1000, 2); // ms
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2); // MB
        $queries = DB::getQueries();
        $totalQueryTime = round(array_sum(array_column($queries, 'duration')), 2);

        echo "<div style='
            font-family: monospace;
            background: #1e1e1e;
            color: #eee;
            padding: 1rem;
            font-size: 14px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            border-top: 2px solid #4caf50;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.4);
        '>";

        echo "<strong>🌿 SproutPHP DebugBar</strong> ";
        echo " | Method: <span style='color:#6cf'>" . $_SERVER['REQUEST_METHOD'] . "</span>";
        echo " | URI: <span style='color:#6cf'>" . $_SERVER['REQUEST_URI'] . "</span>";
        echo " | Queries: <span style='color:#f90'>" . count($queries) . "</span>";
        echo " | Query Time: {$totalQueryTime}ms";
        echo " | Page Time: {$executionTime}ms";
        echo " | Memory: {$memoryUsage}MB";

        // List queries
        if (!empty($queries)) {
            echo "<details style='margin-top:1rem;'><summary>🗃 Queries</summary>";
            foreach ($queries as $i => $q) {
                echo "<div style='margin:5px 0;'>";
                echo "<code style='color:#8f8'>#{$i}</code> " . htmlspecialchars($q['sql']) . "<br>";
                if (!empty($q['params'])) {
                    echo "<small>Params: " . json_encode($q['params']) . "</small><br>";
                }
                if (!empty($q['caller']['file'])) {
                    echo "<small>↳ " . $q['caller']['file'] . " : " . $q['caller']['line'] . "</small>";
                }
                echo "</div>";
            }
            echo "</details>";
        }

        echo "</div>";
    }
}
