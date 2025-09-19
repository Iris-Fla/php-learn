<?php

declare(strict_types=1);

namespace App;

class Config
{
    //
    private static ?array $config = null;

    //
    public static function get(string $name, mixed $default = null): mixed
    {
        // 初期読み込み
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config.php';
        }

        // 設定値の取得
        return self::$config[$name] ?? $default;
    }
}
