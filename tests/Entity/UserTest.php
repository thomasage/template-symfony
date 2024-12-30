<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

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
            ->setRoles(['ROLE_GUEST'])
            ->setTwoFactorsAuthentication(true);
        $user->eraseCredentials();
        $user->setEmailAuthCode('123456');

        self::assertNotNull($user->getLastLoginAt());
        self::assertNull($user->getId());
        self::assertSame('123456', $user->getEmailAuthCode());
        self::assertSame('test', $user->getPassword());
        self::assertSame('test@test.com', $user->getEmail());
        self::assertSame('test@test.com', $user->getEmailAuthRecipient());
        self::assertSame('test@test.com', $user->getUserIdentifier());
        self::assertSame(36, \strlen($user->getUuid()->toString()));
        self::assertSame(['ROLE_GUEST', 'ROLE_USER'], $user->getRoles());
        self::assertTrue($user->hasTwoFactorsAuthentication());
        self::assertTrue($user->isEmailAuthEnabled());
    }

    public function testShouldThrowAnExceptionWithoutEmailAuthCode(): void
    {
        $user = new User();

        $this->expectException(\LogicException::class);

        $user->getEmailAuthCode();
    }
}
