<?php

declare(strict_types=1);

namespace App\Domain\Event;

class PlasterStatueContest2025Policy extends AbstractEventPolicy
{
    // イベントタイトルの取得
    public function eventTitle(): string
    {
        return '2025年石膏像デッサン選手権';
    }
}
