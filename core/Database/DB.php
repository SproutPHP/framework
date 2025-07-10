<?php

namespace Core\Database;

use PDO;
use PDOException;

class DB
{
    protected static $pdo;

    public static function connection()
    {
        if (self::$pdo) return self::$pdo;

        $host = env('DB_HOST', 'localhost');
        $db = env('DB_NAME', 'sprout');
        $user = env('DB_USER', 'root');
        $pass = env('DB_PASS', '');
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            self::$pdo = new PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        } catch (PDOException $ex) {
            die("❌ DB Connection failed: " . $ex->getMessage());
        }
    }

    public static function query($sql, $params = [])
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public static function fetch($sql, $params = [])
    {
        return self::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchOne($sql, $params = [])
    {
        return self::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert($sql, $params = [])
    {
        self::query($sql, $params);
        return self::connection()->lastInsertId();
    }
    
    public static function update($sql, $params = [])
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function delete($sql, $params = [])
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function exists($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchColumn() !== false;
    }
}
