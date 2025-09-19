<?php

declare(strict_types=1);

require_once __DIR__ . "/../../app/initialize.php";

// 認可チェック
if (false === isset($_SESSION['admin_logged_in'])) {
    // ログイン画面へ
    header('Location: /admin/index.php');
    exit;
}

// 表示
echo $twig->render('admin/top.twig');
