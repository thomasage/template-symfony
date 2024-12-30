<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Message\EmailNotification;
use App\MessageHandler\EmailNotificationHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mime\RawMessage;

#[CoversClass(EmailNotificationHandler::class)]
final class EmailNotificationHandlerTest extends KernelTestCase
{
    public function testShouldSendEmailNotification(): void
    {
        self::bootKernel();

        $message = new EmailNotification('Hello!');

        $handler = self::getContainer()->get(EmailNotificationHandler::class);
        \assert($handler instanceof EmailNotificationHandler);

        $handler($message);

        self::assertEmailCount(1);

        $email = self::getMailerMessage();
        \assert($email instanceof RawMessage);

        self::assertEmailTextBodyContains($email, 'Hello!');
    }
}
