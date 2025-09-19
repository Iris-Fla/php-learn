<?php

declare(strict_types=1);

require_once __DIR__ . "/../app/initialize.php";

// セッションの内容を確認
$input = $_SESSION['input'] ?? [];
$errord = $_SESSION['errord'] ?? [];
// セッションの内容をクリア
unset($_SESSION['input'], $_SESSION['errord']);

// 表示
echo $twig->render('index.twig', [
    'input' => $input,
    'errord' => $errord,
]);
