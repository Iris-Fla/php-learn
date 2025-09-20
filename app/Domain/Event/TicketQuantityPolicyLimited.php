<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Config;

class TicketQuantityPolicyLimited implements TicketQuantityPolicyInterface
{
    // 予約可能なチケットの枚数か
    public function canReserve(int $quantity): bool
    {
        // 最大枚数の把握
        $event = Config::get('event');
        if ($event === null) {
            throw new \RuntimeException('Event config not found');
        }
        $max = $event['quantity_policy']['options']['max_per_order'] ?? null;
        if (!is_int($max) || $max <= 0) {
            throw new \RuntimeException('Invalid max_per_order config');
        }

        // 制限あり
        return $quantity <= $max;
    }
}
