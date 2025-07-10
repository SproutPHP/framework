<?php

use Core\Database\DB;

return new Class {
        public function up() {
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