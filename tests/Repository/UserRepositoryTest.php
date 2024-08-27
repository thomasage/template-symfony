<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(UserRepository::class)]
final class UserRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    public function testShouldUpgradePassword(): void
    {
        self::bootKernel();

        UserFactory::createOne([
            'email' => 'john@doe.com',
            'password' => 'initial',
        ]);

        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        assert($passwordHasher instanceof UserPasswordHasherInterface);

        $userRepository = self::getContainer()->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);

        $user = $userRepository->findOneBy(['email' => 'john@doe.com']);
        self::assertInstanceOf(User::class, $user);

        $hashedPassword = $passwordHasher->hashPassword($user, 'final');

        $userRepository->upgradePassword($user, $hashedPassword);

        self::assertSame($hashedPassword, $user->getPassword());
    }

    public function testShouldThrowExceptionWhenNotUser(): void
    {
        self::bootKernel();

        $user = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return '';
            }
        };

        $this->expectException(UnsupportedUserException::class);

        $userRepository = self::getContainer()->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);

        $userRepository->upgradePassword($user, '');
    }
}
