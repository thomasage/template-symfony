<?php

declare(strict_types=1);

namespace App\Tests\Double;

use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;

final readonly class FakeTotpAuthenticator implements TotpAuthenticatorInterface
{
    public const string VALID_CODE = '012345';

    public function checkCode(TwoFactorInterface $user, string $code): bool
    {
        return self::VALID_CODE === $code;
    }

    public function getQRContent(TwoFactorInterface $user): string
    {
        return '';
    }

    public function generateSecret(): string
    {
        return 'test';
    }
}
