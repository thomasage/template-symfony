<?php

declare(strict_types=1);

namespace App\Controller\Security\ResetPassword;

use App\Entity\User;
use App\Form\ResetPasswordRequestFormData;
use App\Form\ResetPasswordRequestFormType;
use App\Message\ResetPasswordRequest;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

final class RequestController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly UserRepository $userRepository,
    ) {}

    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function __invoke(Request $request): Response
    {
        $data = new ResetPasswordRequestFormData();

        $form = $this->createForm(ResetPasswordRequestFormType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail($data->email);
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData): RedirectResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $emailFormData]);

        // Do not reveal whether a user account was found or not.
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     '%s - %s',
            //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

            return $this->redirectToRoute('app_check_email');
        }

        $this->bus->dispatch(
            new ResetPasswordRequest(
                email: $user->getEmail(),
                resetToken: $resetToken->getToken(),
                resetTokenExpirationMessageKey: $resetToken->getExpirationMessageKey(),
                resetTokenExpirationMessageData: $resetToken->getExpirationMessageData(),
            ),
        );

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
