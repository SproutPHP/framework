<?php

namespace Core\Console\Commands;

class MakeModel
{
    public static function handle($name, $flags = [])
    {
        $class = ucfirst($name);
        $tableName = strtolower($name);
        $dir = __DIR__ . "/../../../app/Models";
        $path = "{$dir}/{$class}.php";

        foreach ($flags as $flag) {
            if (str_starts_with($flag, '--table=')) {
                $tableName = explode('=', $flag)[1];
            }
        }

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($path)) {
            echo "❌ Model already exists: {$class}.php\n";
            exit(1);
        }

        $template = $tableName ?
            <<<PHP
                    <?php

                    namespace App\Models;

                    use Core\Database\DB;

                    class {$class}
                    {
                        protected static \$table = '{$tableName}';

                        public static function all()
                        {
                            return DB::fetch("SELECT * FROM " . self::\$table);
                        }

                        public static function find(\$id)
                        {
                            return DB::fetchOne("SELECT * FROM " . self::\$table . " WHERE id = ?", [\$id]);
                        }
                    }
                    PHP
            : <<<PHP
                    <?php

                    namespace App\Models;

                    class {$class}
                    {
                        // Add your properties and methods
                    }
                    PHP;

        file_put_contents($path, $template);
        echo "✅ Model created: app/Models/{$class}.php\n";

        if ($flags) {
            MakeMigration::handle("create_{$tableName}_table", $tableName);
        }
    }
}
