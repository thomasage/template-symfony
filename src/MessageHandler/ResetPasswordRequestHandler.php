<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ResetPasswordRequest;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ResetPasswordRequestHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(ResetPasswordRequest $message): void
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($message->email)
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'expirationMessageData' => $message->resetTokenExpirationMessageData,
                'expirationMessageKey' => $message->resetTokenExpirationMessageKey,
                'resetToken' => $message->resetToken,
            ]);

        $this->mailer->send($email);
    }
}
