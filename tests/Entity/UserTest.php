<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use LogicException;

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    public function testShouldSupportUserProperties(): void
    {
        $user = new User();
        $user
            ->setEmail('test@test.com')
            ->setLastLoginAt()
            ->setPassword('test')
            ->setRoles(['ROLE_GUEST']);
        $user->eraseCredentials();

        self::assertNotNull($user->getLastLoginAt());
        self::assertNull($user->getId());
        self::assertSame('test', $user->getPassword());
        self::assertSame('test@test.com', $user->getEmail());
        self::assertSame('test@test.com', $user->getUserIdentifier());
        self::assertSame(36, \strlen($user->getUuid()->toString()));
        self::assertSame(['ROLE_GUEST', 'ROLE_USER'], $user->getRoles());
    }

    public function testShouldThrowAnExceptionWithoutEmailAuthCode(): void
    {
        $user = new User();

        $this->expectException(LogicException::class);

        $user->getEmailAuthCode();
    }

    public function testShouldEnableTwoFactorsAuthenticationTotp(): void
    {
        $user = new User();
        $user->setEmail('test@test.com');

        $user->setTwoFactorsAuthenticationTotpSecret('test');
        $user->enableTwoFactorsAuthenticationTotp();
        $totpConfiguration = $user->getTotpAuthenticationConfiguration();

        self::assertTrue($user->isTotpAuthenticationEnabled());
        self::assertSame('test@test.com', $user->getTotpAuthenticationUsername());
        self::assertInstanceOf(TotpConfiguration::class, $totpConfiguration);
        self::assertSame('test', $totpConfiguration->getSecret());
        self::assertSame('sha1', $totpConfiguration->getAlgorithm());
        self::assertSame(20, $totpConfiguration->getPeriod());
        self::assertSame(6, $totpConfiguration->getDigits());
    }

    public function testShouldDisableTwoFactorsAuthenticationTotp(): void
    {
        $user = new User();

        $user->disableTwoFactorsAuthenticationTotp();
        $totpConfiguration = $user->getTotpAuthenticationConfiguration();

        self::assertFalse($user->isTotpAuthenticationEnabled());
        self::assertNull($totpConfiguration);
    }

    public function testShouldEnableTwoFactorsAuthenticationEmail(): void
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setEmailAuthCode('test');
        $user->enableTwoFactorsAuthenticationEmail();

        self::assertTrue($user->hasTwoFactorsAuthentication());
        self::assertSame('test@test.com', $user->getEmailAuthRecipient());
        self::assertSame('test', $user->getEmailAuthCode());
    }
}
