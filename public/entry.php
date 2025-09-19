<?php

declare(strict_types=1);

require_once __DIR__ . "/../app/initialize.php";

use App\DbConnection;

// tokenの把握
if ('' === ($token = strval($_GET['token'] ?? ''))) {
    // tokenがないのでinputに飛ばす
    header('Location: /index.php');
    exit;
}

/* tokenの確認 */
// DBハンドルの取得
try {
    $dbh = DbConnection::get();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}
try {
    // データの取得
    // プリペアドステートメント
    $sql = 'SELECT * FROM ticket_purchases WHERE token = :token;';
    $pre = $dbh->prepare($sql);
    //
    $pre->bindValue(':token', $token, PDO::PARAM_STR);
    //
    $pre->execute();
    $datum = $pre->fetch();
} catch (\PDOException $e) {
    // XXX 暫定: 本来はlogに出力する & エラーページを出力する
    echo $e->getMessage();
    exit;
}

// なかったらエラー出力
if (false === $datum) {
    echo $twig->render('entry_error.twig');
    exit;
}

// あったら最低限の情報表示
echo $twig->render('entry.twig', [
    'purchaser_name' => $datum['purchaser_name'],
    'quantity' => (int)$datum['quantity'],
]);
