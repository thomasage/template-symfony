<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(UserRepository::class)]
final class UserRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    public function testShouldUpgradePassword(): void
    {
        $user = new User();
        $user->setEmail('example@example.com');

        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        \assert($passwordHasher instanceof UserPasswordHasherInterface);

        $newPassword = $passwordHasher->hashPassword($user, 'newPassword');

        $repository = self::getContainer()->get(UserRepository::class);
        \assert($repository instanceof UserRepository);

        $repository->upgradePassword($user, $newPassword);

        self::assertSame($newPassword, $user->getPassword());
    }
}
