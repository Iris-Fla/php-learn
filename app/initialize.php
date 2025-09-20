<?php

// initialize.php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Config;

// セッション開始
ob_start();
session_start();

// テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
  // 開発時だけ有効化
  // 'strict_variables' => true,
]);

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// セキュリティ用に「入れて」と言われたヘッダ
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// イベントポリシーの把握
$event = Config::get('event');
$event_policy = new $event['policy_class'](
    new $event['quantity_policy']['class'](),
);