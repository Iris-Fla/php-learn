<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventPolicyInterface
{
    // イベントタイトルの取得
    public function eventTitle(): string;
    // 予約可能なチケットの枚数か
    public function canReserveQuantity(int $quantity): bool;

    // emailアドレスのバリデーション
    public function emailValidate(string $email): string|false;
}
