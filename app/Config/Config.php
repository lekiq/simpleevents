<?php

namespace SimpleEvents\Config;

use Dotenv\Dotenv;

class Config
{
    private static array $config = [];

    public static function load(): void
    {

        Dotenv::createImmutable(dirname(__DIR__, 2))->load();
        self::$config['DB_PATH'] = $_ENV['DB_PATH'] ?? null;
    }

    public static function get(string $key): mixed
    {
        return self::$config[$key] ?? null;
    }
}
