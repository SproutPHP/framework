<?php

namespace Core\Console\Commands;

class Build
{
    public static function run()
    {
        $startTime = microtime(true);
        $projectSize = self::getDirectorySize('.');

        echo "\n🌱 SproutPHP Build: Starting production build...\n";

        // 1. Clean/Create build/ directory
        self::cleanBuildDir();
        self::createBuildDir();

        // 2. Copy only runtime files/folders to build/
        $include = [
            'app', 'core', 'config', 'public', 'routes', 'storage', 'vendor', '.env', 'composer.json', 'composer.lock'
        ];
        foreach ($include as $item) {
            self::copyToBuild($item);
        }

        echo "\n🌱 SproutPHP Build: Runtime files copied.\n";

        // 3. Remove dev files from build/
        self::removeDevFiles();

        // 4. Minify all .twig, .css, .js, .php files in build/
        self::minifyFiles('build');

        // 5. Run composer install --no-dev --optimize-autoloader in build/
        self::composerProdInstall();

        // 6. Precompile Twig templates
        self::precompileTwigTemplates();

        // 7. Print deployment instructions and summary
        $buildSize = self::getDirectorySize('build');
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        self::printSummary($projectSize, $buildSize, $duration);

        echo "\n🌱 SproutPHP Build: Build process complete.\n";
    }

    private static function cleanBuildDir()
    {
        if (is_dir('build')) {
            self::rrmdir('build');
        }
    }

    private static function createBuildDir()
    {
        mkdir('build', 0777, true);
    }

    private static function copyToBuild($item)
    {
        if (!file_exists($item)) return;
        $dest = 'build/' . $item;
        if (is_dir($item)) {
            self::rcopy($item, $dest);
        } else {
            if (!is_dir(dirname($dest))) {
                mkdir(dirname($dest), 0777, true);
            }
            copy($item, $dest);
        }
    }

    // Recursively remove a directory
    private static function rrmdir($dir)
    {
        foreach (array_diff(scandir($dir), ['.', '..']) as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                self::rrmdir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    // Recursively copy a directory
    private static function rcopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0777, true);
        while (false !== ($file = readdir($dir))) {
            if ($file == '.' || $file == '..') continue;
            $srcPath = "$src/$file";
            $dstPath = "$dst/$file";
            if (is_dir($srcPath)) {
                self::rcopy($srcPath, $dstPath);
            } else {
                copy($srcPath, $dstPath);
            }
        }
        closedir($dir);
    }

    private static function removeDevFiles()
    {
        $remove = [
            'core/Console', // Remove all of core/Console
            'tests',
            'docs',
            '.github',
            '.git',
        ];
        foreach ($remove as $item) {
            $path = 'build/' . $item;
            if (is_dir($path)) {
                self::rrmdir($path);
            } elseif (is_file($path)) {
                unlink($path);
            }
        }
        // Remove all .md files recursively
        self::removeFilesByPattern('build', '/\.md$/i');
        // Remove all .stub files recursively
        self::removeFilesByPattern('build', '/\.stub$/i');
        // Remove all .log files recursively
        self::removeFilesByPattern('build', '/\.log$/i');
    }

    private static function removeFilesByPattern($dir, $pattern)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $path = "$dir/$file";
            if (is_dir($path)) {
                self::removeFilesByPattern($path, $pattern);
            } elseif (preg_match($pattern, $file)) {
                unlink($path);
            }
        }
    }

    private static function minifyFiles($dir)
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($rii as $file) {
            if ($file->isDir()) continue;
            $path = $file->getPathname();
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $contents = file_get_contents($path);
            $minified = $contents;
            switch ($ext) {
                case 'php':
                    // SKIP minifying PHP files to avoid breaking code
                    continue 2;
                case 'twig':
                    // Remove Twig comments and extra whitespace
                    $minified = preg_replace('/\{#.*?#\}/s', '', $minified); // twig comments
                    $minified = preg_replace('/\s+/', ' ', $minified); // whitespace
                    break;
                case 'css':
                    // Remove CSS comments and whitespace
                    $minified = preg_replace('#/\*.*?\*/#s', '', $minified); // block comments
                    $minified = preg_replace('/\s+/', ' ', $minified); // whitespace
                    break;
                case 'js':
                    // Remove JS comments and whitespace
                    $minified = preg_replace('#/\*.*?\*/#s', '', $minified); // block comments
                    $minified = preg_replace('#//.*#', '', $minified); // line comments
                    $minified = preg_replace('/\s+/', ' ', $minified); // whitespace
                    break;
            }
            file_put_contents($path, trim($minified));
        }
    }

    private static function composerProdInstall()
    {
        // Remove post-install-cmd from composer.json in build
        $composerJson = 'build/composer.json';
        if (file_exists($composerJson)) {
            $json = json_decode(file_get_contents($composerJson), true);
            if (isset($json['scripts']['post-install-cmd'])) {
                unset($json['scripts']['post-install-cmd']);
                file_put_contents($composerJson, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }
        echo "\n🌱 Running composer install --no-dev --optimize-autoloader in build/...\n";
        $cmd = 'cd build && composer install --no-dev --optimize-autoloader';
        system($cmd);
    }

    private static function precompileTwigTemplates()
    {
        echo "\n🌱 Precompiling Twig templates (in subprocess)...\n";
        $script = 'build/scripts/precompile-twig.php';
        if (!file_exists($script)) {
            // Write the script to build/scripts/precompile-twig.php
            if (!is_dir('build/scripts')) {
                mkdir('build/scripts', 0777, true);
            }
            file_put_contents($script, self::twigPrecompileScriptContent());
        }
        // Run the script in a separate PHP process
        system('php ' . escapeshellarg($script));
    }

    private static function twigPrecompileScriptContent()
    {
        return <<<'EOT'
                    <?php
                    // Precompile Twig templates for production build
                    require_once __DIR__ . '/../vendor/autoload.php';
                    require_once __DIR__ . '/../core/Support/helpers.php';
                    $viewsDir = __DIR__ . '/../app/Views';
                    $cacheDir = __DIR__ . '/../storage/twig-cache';
                    if (!is_dir($cacheDir)) {
                        mkdir($cacheDir, 0777, true);
                    }
                    $loader = new \Twig\Loader\FilesystemLoader($viewsDir);
                    $twig = new \Twig\Environment($loader, [
                        'cache' => $cacheDir,
                        'auto_reload' => false,
                        'debug' => false,
                    ]);
                    // Register all user-defined functions as Twig functions
                    $functions = get_defined_functions()['user'];
                    foreach ($functions as $functionName) {
                        if (function_exists($functionName)) {
                            $twig->addFunction(new \Twig\TwigFunction($functionName, $functionName));
                        }
                    }
                    $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsDir));
                    foreach ($rii as $file) {
                        if ($file->isDir()) continue;
                        $path = $file->getPathname();
                        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'twig') {
                            $relPath = ltrim(str_replace($viewsDir, '', $path), '/\\');
                            try {
                                $twig->load($relPath);
                                echo "Precompiled: $relPath\n";
                            } catch (\Exception $e) {
                                echo "Twig precompile error in $relPath: " . $e->getMessage() . "\n";
                            }
                        }
                    }
                    echo "Twig templates precompiled and cache warmed up.\n";
                    EOT;
    }

    private static function getDirectorySize($dir)
    {
        $size = 0;
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($rii as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    private static function printSummary($projectSize = 0, $buildSize = 0, $duration = 0)
    {
        echo "\n🌱 Build complete!\n";
        echo "\nProject size:   " . self::formatBytes($projectSize);
        echo "\nBuild size:     " . self::formatBytes($buildSize);
        if ($projectSize > 0) {
            $reduction = $projectSize > 0 ? 100 - round(($buildSize / $projectSize) * 100, 2) : 0;
            echo "\nSize reduced:   {$reduction}%";
        }
        echo "\nTime taken:     " . round($duration, 2) . " seconds";
        echo "\n\nTo deploy your app, upload the build/ directory to your server.";
        echo "\nSet your web server's document root to build/public/";
        echo "\nYour entry point is build/public/index.php";
        echo "\n\n🌱 SproutPHP - Happy Deployment! 🚀\n";
    }

    private static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = $bytes > 0 ? floor(log($bytes) / log(1024)) : 0;
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
} 