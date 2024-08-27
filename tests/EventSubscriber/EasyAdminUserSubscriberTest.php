<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\Entity\User;
use App\EventSubscriber\EasyAdminUserSubscriber;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[CoversClass(EasyAdminUserSubscriber::class)]
final class EasyAdminUserSubscriberTest extends KernelTestCase
{
    public function testShouldHashPassword(): void
    {
        self::bootKernel();

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        $user = new User();
        $user->setPassword('plain_password');

        $subscriber = new EasyAdminUserSubscriber($passwordHasher);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch(new BeforeEntityPersistedEvent($user));

        self::assertSame('hashed_password', $user->getPassword());
    }

    public function testShouldNotHandleOtherEntities(): void
    {
        self::bootKernel();

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->never())
            ->method('hashPassword');

        $subscriber = new EasyAdminUserSubscriber($passwordHasher);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch(new BeforeEntityPersistedEvent(new \stdClass()));
    }
}
