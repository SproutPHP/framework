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
        if (!env('APP_DEBUG'))
            return;

        $endTime = microtime(true);
        $startTime = defined('REQUEST_START') ? REQUEST_START : SPROUT_START;
        $executionTime = round(($endTime - $startTime) * 1000, 2); // ms
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2); // MB
        $queries = DB::getQueries();
        $totalQueryTime = round(array_sum(array_column($queries, 'duration')), 2);

        echo "<div id='sproutphp-debugbar' style='
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
    transition: transform 0.3s cubic-bezier(.4,2,.6,1), opacity 0.2s;
    transform: translateY(100%); opacity: 0; display: none;
'>";

        // Toggle button
        // Top-right of debugbar
        // When hidden, show a floating button at bottom right

        echo "<button id='sproutphp-debugbar-toggle' style='
    position: absolute;
    top: 8px;
    right: 16px;
    background: #222;
    color: #eee;
    border: 1px solid #444;
    border-radius: 4px;
    padding: 0.2em 0.7em;
    font-size: 13px;
    cursor: pointer;
    z-index: 10001;
'>Hide</button>";

        echo "<strong>🌿 SproutPHP DebugBar</strong> ";
        echo " | Method: <span style='color:#6cf'>" . $_SERVER['REQUEST_METHOD'] . "</span>";
        echo " | URI: <span id='sproutphp-debugbar-uri' style='color:#6cf'>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</span>";
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

        // End debugbar div

        // Floating show button (initially hidden)
        echo "<button id='sproutphp-debugbar-show' style='
    display: block;
    position: fixed;
    bottom: 12px;
    right: 18px;
    background: #222;
    color: #eee;
    border: 1px solid #444;
    border-radius: 20px;
    padding: 0.4em 1.2em;
    font-size: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    cursor: pointer;
    z-index: 10001;
'>Show DebugBar 🍃</button>";

        // Inline script to update URI after HTMX navigation
        echo "<script>
        function updateSproutDebugbarUri() {
            var el = document.getElementById('sproutphp-debugbar-uri');
            if (el) {
                el.textContent = window.location.pathname + window.location.search;
            }
        }
        updateSproutDebugbarUri();
        document.body.addEventListener('htmx:afterSettle', updateSproutDebugbarUri);
        </script>";

        // Inline script for toggle

        echo "<script>
(function() {
  var bar = document.getElementById('sproutphp-debugbar');
  var toggle = document.getElementById('sproutphp-debugbar-toggle');
  var showBtn = document.getElementById('sproutphp-debugbar-show');
  if (!bar || !toggle || !showBtn) return;
  // By default, showBtn is visible, bar is hidden
  showBtn.style.display = 'block';
  bar.style.transform = 'translateY(100%)';
  bar.style.opacity = '0';
  bar.style.display = 'none';

  showBtn.addEventListener('click', function() {
    bar.style.display = 'block';
    setTimeout(function() {
      bar.style.transform = 'translateY(0)';
      bar.style.opacity = '1';
    }, 10);
    showBtn.style.display = 'none';
  });
  toggle.addEventListener('click', function() {
    bar.style.transform = 'translateY(100%)';
    bar.style.opacity = '0';
    setTimeout(function() {
      bar.style.display = 'none';
      showBtn.style.display = 'block';
    }, 300);
  });
})();
</script>";
    }
}
