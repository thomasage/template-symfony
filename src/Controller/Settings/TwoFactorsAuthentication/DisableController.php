<?php

declare(strict_types=1);

namespace App\Controller\Settings\TwoFactorsAuthentication;

use App\Entity\User;
use App\Form\Settings\TwoFactorsAuthenticationConfirmationData;
use App\Form\Settings\TwoFactorsAuthenticationConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DisableController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TotpAuthenticatorInterface $totpAuthenticator,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/settings/2fa/disable', name: 'app_settings_2fa_disable', methods: ['GET', 'POST'])]
    public function __invoke(#[CurrentUser] User $user, Request $request): Response
    {
        if (!$user->isTotpAuthenticationEnabled()) {
            return $this->redirectToRoute('app_settings_profile');
        }

        $data = new TwoFactorsAuthenticationConfirmationData();

        $form = $this->createForm(TwoFactorsAuthenticationConfirmationType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted()
            && $form->isValid()
            && $this->totpAuthenticator->checkCode($user, $data->code)) {
            $user->disableTwoFactorsAuthenticationTotp();
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('notifications.2fa_disabled', domain: 'settings'));

            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings/two_factors_authentication/disable.html.twig', [
            'form' => $form,
        ]);
    }
}
