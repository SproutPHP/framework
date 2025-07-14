<?php

namespace Core\Database;

use PDO;
use PDOException;

class DB
{
    protected static $pdo;
    protected static $queries = [];

    public static function connection()
    {
        if (self::$pdo) return self::$pdo;

        $connection = config('database.default', 'mysql');
        $config = config("database.connections.$connection");
        
        if (!$config) {
            die("❌ Database connection '$connection' not found in config.");
        }

        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;
        $database = $config['database'] ?? 'sprout';
        $username = $config['username'] ?? 'root';
        $password = $config['password'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=$charset";

        try {
            self::$pdo = new PDO($dsn, $username, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        } catch (PDOException $ex) {
            die("❌ DB Connection failed: " . $ex->getMessage());
        }
    }

    public static function getQueries()
    {
        return self::$queries;
    }

    public static function resetQueryLog()
    {
        self::$queries = [];
    }

    public static function logQuery($sql, $params, $start, $end)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);

        self::$queries[] = [
            'sql' => $sql,
            'params' => $params,
            'duration' => round(($end - $start) * 1000, 2),
            'caller' => $trace[1] ?? []
        ];
    }

    public static function query($sql, $params = [])
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public static function fetch($sql, $params = [])
    {
        $start = microtime(true);
        $stmt = self::query($sql, $params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchOne($sql, $params = [])
    {
        $start = microtime(true);
        $stmt = self::query($sql, $params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert($sql, $params = [])
    {
        $start = microtime(true);
        self::query($sql, $params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);

        return self::connection()->lastInsertId();
    }

    public static function update($sql, $params = [])
    {
        $stmt = self::connection()->prepare($sql);

        $start = microtime(true);
        $stmt->execute($params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);

        return $stmt->rowCount();
    }

    public static function delete($sql, $params = [])
    {
        $start = microtime(true);
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);

        return $stmt->rowCount();
    }

    public static function exists($sql, $params = [])
    {
        $start = microtime(true);
        $stmt = self::query($sql, $params);
        $end = microtime(true);
        self::logQuery($sql, $params, $start, $end);
        return $stmt->fetchColumn() !== false;
    }
}
