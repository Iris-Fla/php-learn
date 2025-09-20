<?php

// TicketTokenUsage.php

declare(strict_types=1);

namespace App\Models;

use App\DbConnection;

class TicketTokenUsage
{
    // チケットを消費する
    // 消費できない(すでに使用済)ならfalseが返る
    public static function consumeToken(string $token): bool
    {
        try {
            $dbh = DbConnection::get();

            // トランザクションの開始
            $dbh->beginTransaction();

            // 使用済かどうか？
            $sql = 'SELECT * FROM ticket_token_usages WHERE token = :token FOR UPDATE;';
            $pre = $dbh->prepare($sql);
            //
            $pre->bindValue(':token', $token, \PDO::PARAM_STR);
            //
            $pre->execute();
            $tokenUsage = $pre->fetch();
            //
            if (false !== $tokenUsage) {
                $dbh->rollBack(); // トランザクションのキャンセル
                return false;
            }

            // チケットを使用済にする
            $sql = 'INSERT INTO ticket_token_usages(token, created_at, updated_at)
            VALUES(:token, :created_at, :updated_at);';
            $pre = $dbh->prepare($sql);
            //
            $pre->bindValue(':token', $token, \PDO::PARAM_STR);
            $now = date('Y-m-d H:i:s');
            $pre->bindValue(':created_at', $now, \PDO::PARAM_STR);
            $pre->bindValue(':updated_at', $now, \PDO::PARAM_STR);
            //
            $pre->execute();

            // コミット
            $dbh->commit();
        } catch (\PDOException $e) {
            // XXX 暫定: 本来はlogに出力する & エラーページを出力する
            echo $e->getMessage();
            exit;
        }
        return true;
    }
}
