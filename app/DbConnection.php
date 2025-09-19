<?php

// DbConnection.php

declare(strict_types=1);

namespace App;

use App\Config;
use PDO;

class DbConnection
{
    // Singletonパターンっぽく
    private function __construct()
    {
    }

    //
    public static function get(): \PDO
    {
        static $dbh = null;
        if ($dbh === null) {
            // DB接続処理
            $db_config = Config::get('db');
            $dsn = "mysql:dbname={$db_config['database']};host={$db_config['host']};port={$db_config['port']};charset={$db_config['charset']}";
            $opt = [
                // セキュリティ上必須
                PDO::ATTR_EMULATE_PREPARES => false,  // エミュレート無効
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,  // 複文無効
                // お好みで
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // データ取得モード
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーが発生した場合、PDOException をスロー
            ];
            $dbh = new \PDO($dsn, $db_config['user'], $db_config['pass'], $opt);
        }
        return $dbh;
    }
}
