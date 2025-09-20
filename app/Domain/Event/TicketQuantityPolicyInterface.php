<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface TicketQuantityPolicyInterface
{
    // 予約可能なチケットの枚数か
    public function canReserve(int $quantity): bool;
}
