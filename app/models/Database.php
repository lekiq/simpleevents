<?php

namespace SimpleEvents\Models;

use PDO;
use SimpleEvents\Config\Config;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        Config::load();

        $dbPath = dirname(__DIR__) . Config::get('DB_PATH');

        if (is_null($dbPath) || !file_exists($dbPath)) {
            echo "\nError: Database file not found.";
            exit;
        }

        return self::$connection ??= new PDO(
            'sqlite:' . $dbPath,
            options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function disconnect(): void
    {
        self::$connection = null;
    }
}
