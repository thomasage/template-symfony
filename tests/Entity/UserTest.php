<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(User::class)]
final class UserTest extends KernelTestCase
{
    public function testUserHasProperties(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne(['email' => 'john@doe.com']);
        $user->eraseCredentials();

        self::assertIsInt($user->getId());
        self::assertSame('john@doe.com', $user->getEmail());
        self::assertSame('john@doe.com', $user->getUserIdentifier());
        self::assertSame(['ROLE_USER'], $user->getRoles());
    }
}
