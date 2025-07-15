<?php

namespace Core\Console\Commands;

class SymlinkCreate
{
    public static function handle()
    {
        $publicStorage = __DIR__ . '/../../../public/storage';
        $target = __DIR__ . '/../../../storage/app/public';

        // Remove existing symlink or directory if present
        if (is_link($publicStorage) || is_dir($publicStorage)) {
            if (is_link($publicStorage)) {
                unlink($publicStorage);
            } else {
                // Remove directory recursively
                self::rrmdir($publicStorage);
            }
        }

        // Try PHP's symlink() first (works on Windows with Developer Mode)
        $result = @symlink(realpath($target), $publicStorage);
        if ($result) {
            echo "✅ Symlink created: public/storage → storage/app/public\n";
            return;
        }

        // If on Windows and symlink() failed, try mklink as admin
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cmd = 'mklink /D "' . str_replace('/', '\\', realpath(dirname($publicStorage))) . '\\storage" "' . str_replace('/', '\\', realpath($target)) . '"';
            exec('cmd /c "' . $cmd . '"', $output, $result);
            if ($result === 0) {
                echo "✅ Symlink created: public/storage → storage/app/public\n";
            } else {
                echo "❌ Failed to create symlink on Windows. Try running as administrator or enable Developer Mode.\n";
            }
        } else {
            echo "❌ Failed to create symlink. Try running with sufficient permissions.\n";
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