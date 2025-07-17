<?php

namespace Core\Support;

class Storage
{
    /**
     * Get storage config
     */
    protected static function config()
    {
        return require __DIR__ . '/../../config/storage.php';
    }

    /**
     * Get disk config
     */
    protected static function diskConfig($disk = null)
    {
        $config = self::config();
        $disk = $disk ?: $config['default'];
        return $config['disks'][$disk] ?? $config['disks']['public'];
    }

    /**
     * Resolve a path to absolute, relative to project root if needed
     */
    protected static function resolvePath($path)
    {
        // If already absolute (Unix or Windows)
        if (preg_match('/^(\/|[A-Za-z]:[\\\\\/])/', $path)) {
            return $path;
        }
        // Otherwise, resolve relative to project root (2 levels up from core/Support)
        return dirname(__DIR__, 2) . '/' . ltrim($path, '/');
    }

    /**
     * Save uploaded file
     * @param array $file The $_FILES['your_input'] array
     * @param string $subdir Optional subdirectory (e.g. 'profilepic')
     * @param string|null $disk Disk to use ('public' or 'private')
     * @return string|false The relative path to the saved file, or false on error
     */
    public static function put($file, $subdir = '', $disk = null)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $diskConfig = self::diskConfig($disk);
        $dir = rtrim(self::resolvePath($diskConfig['root']) . '/' . trim($subdir, '/'), '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
        $target = $dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Return relative path for storage (subdir/filename)
            return ($subdir ? trim($subdir, '/') . '/' : '') . $filename;
        }

        return false;
    }

    /**
     * Get the full path to a stored file
     */
    public static function path($relative, $disk = null)
    {
        $diskConfig = self::diskConfig($disk);
        return rtrim(self::resolvePath($diskConfig['root']), '/') . '/' . ltrim($relative, '/');
    }

    /**
     * Get a public URL for a stored file (if disk is public)
     */
    public static function url($relative, $disk = null)
    {
        $diskConfig = self::diskConfig($disk);
        if (($diskConfig['visibility'] ?? 'public') !== 'public') {
            return null; // No public URL for private disk
        }
        $urlPrefix = rtrim($diskConfig['url'] ?? '/storage', '/');
        return $urlPrefix . '/' . ltrim($relative, '/');
    }
}