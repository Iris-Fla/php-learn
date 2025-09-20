<?php

declare(strict_types= 1);

namespace App\Domain\Validate;

class EmailValidatorSimple implements EmailValidator
{
    public function validate(string $email): bool
    {
        // 簡易的なメールアドレスのバリデーション
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }
}