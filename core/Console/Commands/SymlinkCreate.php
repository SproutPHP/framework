<?php

namespace Core\Console\Commands;

class SymlinkCreate
{
    public static function handle()
    {
        $publicStorage = __DIR__ . '/../../../public/storage';
        $target = __DIR__ . '/../../../storage/app/public';

        // Ensure the target directory exists
        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }

        // Remove existing symlink or directory if present
        if (is_link($publicStorage) || is_dir($publicStorage)) {
            if (is_link($publicStorage)) {
                unlink($publicStorage);
            } else {
                // Remove directory recursively
                self::rrmdir($publicStorage);
            }
        }

        // If on Windows and symlink() failed, try mklink as admin
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $resolvedLinkDir = realpath(dirname($publicStorage));
            $resolvedTarget = realpath($target);
            $link = $resolvedLinkDir ? $resolvedLinkDir . '\\storage' : false;

            if (!$resolvedLinkDir || !$resolvedTarget) {
                echo "❌ One or both paths do not exist.\n";
                return;
            }

            // Use /J for junction (works without admin/Dev Mode)
            $cmd = 'mklink /J "' . $link . '" "' . $resolvedTarget . '"';
            exec('cmd /c "' . $cmd . '"', $output, $result);
            if ($result === 0) {
                echo "✅ Symlink (junction) created: public/storage → storage/app/public\n";
            } else {
                echo "❌ Failed to create symlink (junction) on Windows. Try running as administrator or check permissions.\n";
            }
        } else {
            $result = @symlink(realpath($target), $publicStorage);
            if ($result) {
                echo "✅ Symlink created: public/storage → storage/app/public\n";
                return;
            } else {
                echo "❌ Failed to create symlink. Try running with sufficient permissions.\n";
            }
        }
    }

    // Recursively remove a directory
    protected static function rrmdir($dir)
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
}