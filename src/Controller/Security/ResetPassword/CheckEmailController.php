<?php

declare(strict_types=1);

namespace App\Controller\Security\ResetPassword;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

final class CheckEmailController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(private readonly ResetPasswordHelperInterface $resetPasswordHelper) {}

    #[Route('/reset-password/check-email', name: 'app_check_email')]
    public function __invoke(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether a user was found with the given email address or not
        if (!($resetToken = $this->getTokenObjectFromSession()) instanceof \SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }
}
