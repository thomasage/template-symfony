<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\EmailNotification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EmailNotification::class)]
final class EmailNotificationTest extends TestCase
{
    public function testShouldGetContent(): void
    {
        $message = new EmailNotification('Hello!');

        self::assertSame('Hello!', $message->content);
    }
}
