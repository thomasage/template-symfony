<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\EmailNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class EmailNotificationHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(EmailNotification $message): void
    {
        $email = new Email()
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text($message->content);

        $this->mailer->send($email);
    }
}
