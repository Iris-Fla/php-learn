<?php

declare(strict_types=1);

require_once __DIR__ . "/../../app/initialize.php";

use App\Models\TicketPurchase;

// 認可チェック
if (false === isset($_SESSION['admin_logged_in'])) {
    // ログイン画面へ
    header('Location: /admin/index.php');
    exit;
}

/* 一覧取得 */
$list = TicketPurchase::getAll();

// 表示
$base_url = 'http://game.m-fr.net:8080';
echo $twig->render('admin/list.twig', [
    'list' => $list,
    'base_url' => $base_url,
]);
