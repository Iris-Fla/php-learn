<?php

declare(strict_types=1);

namespace App\Domain\Event;

class TicketQuantityPolicyUnlimited implements TicketQuantityPolicyInterface
{
    // 予約可能なチケットの枚数か
    public function canReserve(int $quantity): bool
    {
        // 無制限
        return true;
    }
}
