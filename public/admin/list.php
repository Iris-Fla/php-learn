<?php

declare(strict_types=1);

require_once __DIR__ . "/../../app/initialize.php";

use App\DbConnection;

// 認可チェック
if (false === isset($_SESSION['admin_logged_in'])) {
    // ログイン画面へ
    header('Location: /admin/index.php');
    exit;
}

/* 一覧取得 */
// DB接続情報
try {
    $dbh = DbConnection::get();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

try {
    // データの登録
    // プリペアドステートメント
    $stmt = $dbh->prepare('SELECT * FROM ticket_purchases ORDER BY created_at DESC LIMIT 100');
    $stmt->execute();
    $list = $stmt->fetchAll();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

// 表示
$base_url = 'http://game.m-fr.net:8007/';
echo $twig->render('admin/list.twig', [
    'list' => $list,
    'base_url' => $base_url,
]);
