<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final readonly class ResetPasswordRequest
{
    /**
     * @param int[] $resetTokenExpirationMessageData
     */
    public function __construct(
        public string $email,
        public string $resetToken,
        public string $resetTokenExpirationMessageKey,
        public array $resetTokenExpirationMessageData,
    ) {}
}
