<?php

namespace Core\Support;

class Storage
{
    protected static $baseDir = __DIR__ . '/../../public/uploads';

    /**
     * Save uploaded file
     * @param array $file The $_FILES['your_input'] array
     * @param string $subdir Optional subdirectory (e.g. 'profilepic')
     * @param string|false The relative path to the saved file, or false on error
     */
    public static function put($file, $subdir = '')
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $dir = rtrim(self::$baseDir . '/' . trim($subdir, '/'), '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
        $target = $dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Return relative path for storage
            return ($subdir ? '/' . trim($subdir, '/') : '') . '/' . $filename;
        }

        return false;
    }

    /**
     * Get the full path to a stored file
     */
    public static function path($relative)
    {
        return self::$baseDir . '/' . ltrim($relative, '/');
    }

    /**
     * Get a public URL for a stored file (assuming /uploads is web-accessible/storage is web-accessible)
     */
    public static function url($relative)
    {
        return '/uploads/' . ltrim($relative, '/');
    }
}