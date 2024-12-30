<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\EmailNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $bus) {}

    #[Route('/notification', name: 'app_notification')]
    public function index(): RedirectResponse
    {
        $this->bus->dispatch(new EmailNotification('Hello World!'));

        $this->addFlash('success', 'Notification sent!');

        return $this->redirectToRoute('app_home');
    }
}
