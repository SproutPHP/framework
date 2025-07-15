<?php

namespace Core\Console;

class PostInstall
{
    protected static function download($url, $savePath)
    {
        echo "📦 Downloading $url...\n";
        $data = @file_get_contents($url);

        if ($data === false) {
            echo "❌ Failed to download: $url\n";
            return;
        }

        @mkdir(dirname($savePath), 0777, true);
        file_put_contents($savePath, $data);
        echo "✅ Saved to $savePath\n";
    }

    public static function run()
    {
        // HTMX
        self::download(
            'https://unpkg.com/htmx.org@latest/dist/htmx.min.js',
            __DIR__ . '/../../public/assets/js/sprout.min.js'
        );

        // Pico.css - Prompt user for build type
        echo "\n🌱 PicoCSS Setup — Choose your preferred CSS build:\n\n";
        echo "0) Default Sprout Layout (Minimal PicoCSS)\n";
        echo "1) Minimal (Standard) — pico.min.css\n";
        echo "   [Full utility classes, grid, button styles, default blue theme]\n";
        echo "2) Classless — pico.classless.min.css\n";
        echo "   [No classes needed, just semantic HTML, minimal blog/docs]\n";
        echo "3) Conditional — pico.conditional.min.css\n";
        echo "   [Supports [data-theme], JS toggling, prefers-color-scheme]\n";
        echo "4) Fluid Classless — pico.fluid.classless.min.css\n";
        echo "   [Full-width, no max-width, classless]\n";
        echo "5) Color Theme — e.g. pico.purple.min.css\n";
        echo "6) Classless + Color Theme — e.g. pico.classless.purple.min.css\n";
        echo "7) Conditional + Color Theme — e.g. pico.conditional.purple.min.css\n";
        echo "8) Fluid + Classless + Conditional + Color Theme — e.g. pico.fluid.classless.conditional.purple.min.css\n";
        echo "9) Color Palette Only — pico.colors.min.css\n";
        echo "   [Just variables, add your own styles]\n\n";
        echo "Press Enter to use the default (Minimal PicoCSS).\n";
        echo "Enter the number of your choice [0-9]: ";
        $choice = trim(fgets(STDIN));

        // If user presses Enter or enters 0, use default minimal PicoCSS
        if ($choice === '' || $choice === '0') {
            $base = "https://cdn.jsdelivr.net/npm/@picocss/pico@latest/css/";
            $file = "pico.min.css";
            $url = $base . $file;
            $dest = __DIR__ . '/../../public/assets/css/sprout.min.css';
            self::download($url, $dest);
            return;
        }

        $color = '';
        $needsColor = in_array($choice, ['5', '6', '7', '8']);
        if ($needsColor) {
            echo "Enter color name (amber, blue, cyan, fuchsia, green, grey, indigo, jade, lime, orange, pink, pumpkin, purple, red, sand, slate, violet, yellow, zinc): ";
            $color = strtolower(trim(fgets(STDIN)));
            $validColors = ['amber', 'blue', 'cyan', 'fuchsia', 'green', 'grey', 'indigo', 'jade', 'lime', 'orange', 'pink', 'pumpkin', 'purple', 'red', 'sand', 'slate', 'violet', 'yellow', 'zinc'];
            if (!in_array($color, $validColors)) {
                echo "Invalid color. Defaulting to blue.\n";
                $color = 'blue';
            }
        }

        echo "Use minified version? [Y/n]: ";
        $min = strtolower(trim(fgets(STDIN)));
        $min = ($min === 'n') ? '' : '.min';

        $base = "https://cdn.jsdelivr.net/npm/@picocss/pico@latest/css/";
        $file = '';

        switch ($choice) {
            case '1':
                $file = "pico$min.css";
                break;
            case '2':
                $file = "pico.classless$min.css";
                break;
            case '3':
                $file = "pico.conditional$min.css";
                break;
            case '4':
                $file = "pico.fluid.classless$min.css";
                break;
            case '5':
                $file = "pico.$color$min.css";
                break;
            case '6':
                $file = "pico.classless.$color$min.css";
                break;
            case '7':
                $file = "pico.conditional.$color$min.css";
                break;
            case '8':
                $file = "pico.fluid.classless.conditional.$color$min.css";
                break;
            case '9':
                $file = "pico.colors$min.css";
                break;
            default:
                $file = "pico$min.css";
                break;
        }

        $url = $base . $file;
        $dest = __DIR__ . '/../../public/assets/css/sprout.min.css';

        self::download($url, $dest);

        // Prompt for dark/light mode toggle
        echo "\nWould you like to include a dark/light mode toggle button in your navbar? (y/N): ";
        $includeToggle = strtolower(trim(fgets(STDIN)));
        if ($includeToggle === 'y') {
            $navbarPath = __DIR__ . '/../../app/Views/components/navbar.twig';
            $navbar = file_get_contents($navbarPath);
            // Only add if not already present
            if (strpos($navbar, 'theme-toggle-btn') === false) {
                $toggleBtn = <<<HTML
                            <li>
                            <!-- Dark/Light mode toggle auto-included by SproutPHP installer -->
                            <button id="theme-toggle-btn" aria-label="Toggle dark/light mode" style="background:none;border:none;cursor:pointer;font-size:1.5rem;">
                                <span id="theme-icon">☀️</span>
                            </button>
                            </li>
                            HTML;
                // Insert before </ul> of the right-side nav (last </ul> in file)
                $navbar = preg_replace('/(<\/ul>)(?![\s\S]*<\/ul>)/', "$toggleBtn\n$1", $navbar, 1);
                // Add the script before </div> at the end
                $toggleScript = <<<SCRIPT
                                <script>
                                (function() {
                                const themeBtn = document.getElementById('theme-toggle-btn');
                                const themeIcon = document.getElementById('theme-icon');
                                const html = document.documentElement;
                                if (!themeBtn || !themeIcon) return;
                                function setInitialTheme() {
                                    let theme = localStorage.getItem('theme');
                                    if (!theme) {
                                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                                    }
                                    html.setAttribute('data-theme', theme);
                                    themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
                                }
                                setInitialTheme();
                                themeBtn.addEventListener('click', function() {
                                    const currentTheme = html.getAttribute('data-theme');
                                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                                    html.setAttribute('data-theme', newTheme);
                                    localStorage.setItem('theme', newTheme);
                                    themeIcon.textContent = newTheme === 'dark' ? '🌙' : '☀️';
                                });
                                })();
                                </script>
                                SCRIPT;
                $navbar = preg_replace('/<\/div>\s*$/', "$toggleScript\n</div>", $navbar, 1);
                file_put_contents($navbarPath, $navbar);
                echo "✅ Dark/light mode toggle added to your navbar.\n";
            } else {
                echo "ℹ️  Dark/light mode toggle already present in your navbar.\n";
            }
        } else {
            echo "ℹ️  You can add a dark/light mode toggle later by yourself if you wish.\n";
        }
    }
}
