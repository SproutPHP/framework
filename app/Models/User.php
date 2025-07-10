<?php

namespace App\Models;

use Core\Database\DB;

class User
{
    protected static $table = 'users';

    public static function all()
    {
        return DB::fetch("SELECT * FROM " . self::$table);
    }

    public static function find($id)
    {
        return DB::fetchOne("SELECT * FROM " . self::$table . " WHERE id = ?", [$id]);
    }
}