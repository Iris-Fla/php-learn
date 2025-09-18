<?php

declare(strict_types=1);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// セッション開始
ob_start();
session_start();

// email送信用に、テンプレートエンジンを使う
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// 入力を受け取る
// [TODO] POSTからname/email/quantityを受け取る
// [TODO] 受け取ったデータは、$input 変数に連想配列の形で格納する
$input = [
    'purchaser_name' => $_POST['purchaser_name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'quantity' => $_POST['quantity'] ?? '',
];




/* validate */
$errord = [];
// 氏名の入力
if ($input['purchaser_name'] === '') {
    $errord['purchaser_name'] = '氏名を入力してください';
}

// メアドの確認
// [TODO] emailが「空でないこと」「emailのフォーマットとして適切であること」の確認
if ($input['email'] === '') {
    $errord['email'] = 'メールアドレスを入力してください';
} elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errord['email'] = 'メールアドレスの形式が不正です';
}

// チケットの枚数
// [TODO] quantityが「空でないこと」「整数であること」の確認
if ($input['quantity'] === '') {
    $errord['quantity'] = '枚数を入力してください';
} elseif (!preg_match('/^[1-9][0-9]*$/', $input['quantity'])) {
    $errord['quantity'] = '枚数は1以上の整数で入力してください';
}

// エラーがあった場合、入力フォームに戻す
if (count($errord) > 0) {
    // セッションにエラー内容と入力値を保存しておく
    $_SESSION['errord'] = $errord;
    $_SESSION['input'] = $input;
    // 入力フォームに戻す
    header('Location: index.php');
    exit;
}

// tokenの作成
// [TODO] 「推測不能文字列」として適切なtokenを生成し、$token 変数に格納する
$token = bin2hex(random_bytes(32));

/* DBへの登録 */
// DBハンドルの秀徳
$config = require __DIR__ . '/../config.php';
$db_config = $config['db'];
$dsn = "mysql:dbname={$db_config['database']};host={$db_config['host']};port={$db_config['port']};charset={$db_config['charset']}";

$opt = [
    // セキュリティ上必須
    PDO::ATTR_EMULATE_PREPARES => false,  // エミュレート無効
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,  // 複文無効
    // お好みで
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // データ取得モード
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーが発生した場合、PDOException をスロー
];
try {
    $dbh = new \PDO($dsn, $db_config['user'], $db_config['pass'], $opt);
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

try {
    // データの登録
    // [TODO] ticket_purchases テーブルに登録する
    $dbh = new \PDO($dsn, $db_config['user'], $db_config['pass'], $opt);
    $stmt = $dbh->prepare('INSERT INTO ticket_purchases (purchaser_name, email, quantity, token, created_at, updated_at) VALUES (:purchaser_name, :email, :quantity, :token, :created_at, :updated_at)');
    $stmt->bindValue(':purchaser_name', $input['purchaser_name'], PDO::PARAM_STR);
    $stmt->bindValue(':email', $input['email'], PDO::PARAM_STR);
    $stmt->bindValue(':quantity', (int) $input['quantity'], PDO::PARAM_INT);
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    // mailの送信
    $send_at = (new DateTimeImmutable())->format('Y-m-d H:i:s');
    $stmt->bindValue(':updated_at', $send_at, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $send_at, PDO::PARAM_STR);
    $base_url = 'http://game.m-fr.net:8080/';
    $subject = '【チケット購入完了】チケット購入ありがとうございます';
    $body = $twig->render('ticket_purchase_complete.twig', [
        'purchaser_name' => $input['purchaser_name'],
        'quantity' => $input['quantity'],
        'base_url' => $base_url,
        'token' => $token,
    ]);
    // XXX 本当はここでmail送信をする

    // XXX 今回は実際のmail送信は書かないので「mailを送った履歴」DBへのinsertのみ
    // [TODO] email_send_logs テーブルに登録する
    $stmt = $dbh->prepare('INSERT INTO email_send_logs (updated_at,created_at,email,quantity,ticket_purchase_id,purchaser_name,subject,body,sent_at) VALUES (:updated_at,:created_at,:email, :quantity, :ticket_purchase_id, :purchaser_name, :subject, :body, :sent_at)');
    $stmt->bindValue(':updated_at', $send_at, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $send_at, PDO::PARAM_STR);
    $stmt->bindValue(':email', $input['email'], PDO::PARAM_STR);
    $stmt->bindValue(':quantity', (int) $input['quantity'], PDO::PARAM_INT);
    $stmt->bindValue(':ticket_purchase_id', $dbh->lastInsertId(), PDO::PARAM_INT);
    $stmt->bindValue(':purchaser_name', $input['purchaser_name'], PDO::PARAM_STR);
    $stmt->bindValue(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindValue(':body', $body, PDO::PARAM_STR);
    $stmt->bindValue(':sent_at', $send_at, PDO::PARAM_STR);
    $stmt->execute();


} catch (Exception $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

// 完了ページへのlocation
header('Location: fin_print.php');
