<?php

namespace Core\Console\Commands;

use Core\Database\DB;

class Migrate
{
    public static function handle()
    {
        $migrationsPath = __DIR__ . '/../../../migrations/';
        $migrated = [];

        if (!is_dir($migrationsPath)) {
            echo "❌ No migrations directory found.\n";
            return;
        }

        // Ensure a table to track migrations
        DB::query("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) UNIQUE,
            migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $applied = array_column(DB::fetch("SELECT migration FROM migrations"), 'migration');
        $files = glob($migrationsPath . '*.php');

        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $applied)) continue;

            $migration = require $file;
            if (method_exists($migration, 'up')) {
                $migration->up();
                DB::insert("INSERT INTO migrations (migration) VALUES (?)", [$name]);
                echo "✅ Migrated: $name\n";
            }
        }

        if (empty($files)) echo "⚠️ No migration files found.\n";
    }
}
