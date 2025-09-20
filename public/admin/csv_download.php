<?php

// csv_download.php

declare(strict_types=1);

require_once __DIR__ . '/../../app/initialize.php';

use App\Models\TicketPurchase;

// 認可チェック
if (false === isset($_SESSION['admin_logged_in'])) {
    // ログイン画面へ
    header('Location: /admin/index.php');
    exit;
}

// 一覧のデータ配列(と仮定)
$data = TicketPurchase::getAll();

/* CSVデータ出力 */
// ダウンロードファイル名作成
$dt = date('YmdHis');
$dfn = "ticket.{$dt}.csv";

// ヘッダ出力
header('Content-type: text/csv');
header("Content-Disposition: attachment; filename={$dfn}");

// body出力
$fp = fopen("php://output", "w");
// var_dump($fp);

foreach ($data as $datum) {
    // 文字コード変換
    $datum_sjis = mb_convert_encoding($datum, 'SJIS-win', 'UTF-8');

    //
    fputcsv($fp, $datum_sjis, escape: "");
}

fclose($fp);
