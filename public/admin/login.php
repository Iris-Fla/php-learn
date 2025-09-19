<?php

declare(strict_types=1);

require_once __DIR__ . "/../../app/initialize.php";

// ログイン情報
const LOGIN_ID = 'admin';
// const PASSWORD = 'pass';
const PASSWORD = '$2y$12$Vhah0HZkUJ8Uut9/E7PGQeRwEvDnEW5udj7Bk8PoVRaWFn168bcim'; // passをハッシュ化したもの

// ログインIDとパスワード把握
$login_id = strval($_POST['login_id'] ?? '');
$password = strval($_POST['password'] ?? '');

/* validate */
$errord = [];
if ($login_id === '') {
    $errord['login_id'] = 'ログインIDを入力してください';
}
if ($password === '') {
    $errord['password'] = 'パスワードを入力してください';
}
// validateにエラーがなければログイン確認
if ([] === $errord) {
    if (!password_verify($password, PASSWORD) || ($login_id !== LOGIN_ID)) {
        $errord['login_id/password'] = 'ログインIDかパスワードが違います';
    }
}
if ([] !== $errord) {
    // セッションにエラー内容と入力値を保存しておく
    $_SESSION['errord'] = $errord;
    $_SESSION['input'] = [
        'login_id' => $login_id,
    ];
    // 入力フォームに戻す
    header('Location: /admin/index.php');
    exit;
}

/* ログイン成功 */
// セッションID変更
session_regenerate_id(true);
// ログイン情報をセッションに保存
$_SESSION['admin_logged_in'] = $login_id;
// 管理画面トップへ
header('Location: /admin/top.php');
