<?php

namespace Core\Console\Commands;

class MakeMigration
{
    public static function handle($name, $tableName = 'example')
    {
        $timestamp = date('Ymd_His');
        $fileName = $timestamp . '_' . $name . '.php';
        $path = __DIR__ . '/../../../migrations/' . $fileName;

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $template = <<<PHP
        <?php

        use Core\Database\DB;

        return new class {
            public function up()
            {
                DB::query("CREATE TABLE users (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            /* Other columns */
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");
            }

            public function down()
            {
                DB::query("DROP TABLE IF EXISTS users");
            }
        };
        PHP;
        file_put_contents($path, $template);
        echo "✅ Migration created: migrations/$fileName\n";
    }
}
