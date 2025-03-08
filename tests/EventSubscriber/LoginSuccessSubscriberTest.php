<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\LoginSuccessSubscriber;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use DateTimeImmutable;

#[CoversClass(LoginSuccessSubscriber::class)]
final class LoginSuccessSubscriberTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    public function testShouldHandleLoginSuccessEvent(): void
    {
        self::assertArrayHasKey(LoginSuccessEvent::class, LoginSuccessSubscriber::getSubscribedEvents());
    }

    public function testShouldUpdateLastLoginAt(): void
    {
        $user = UserFactory::createOne();

        $event = $this->createMock(LoginSuccessEvent::class);
        $event
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $subscriber = self::getContainer()->get(LoginSuccessSubscriber::class);
        \assert($subscriber instanceof LoginSuccessSubscriber);

        $subscriber->onLoginSuccessEvent($event);

        self::assertInstanceOf(DateTimeImmutable::class, $user->getLastLoginAt());
    }
}
