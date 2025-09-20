<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Validate\EmailValidator;
abstract class AbstractEventPolicy implements EventPolicyInterface
{
    public function __construct(
        private TicketQuantityPolicyInterface $ticketQuantityPolicy,
        private EmailValidatorInterface $emailValidator
    ) {
    }
    // 予約可能なチケットの枚数か
    public function canReserveQuantity(int $quantity): bool
    {
        return $this->ticketQuantityPolicy->canReserve($quantity);
    }

    //emailアドレスのバリデーション
    public function emailValidate(string $email): string|false
    {
        return $this->emailValidator->validate($email);
    }
}
