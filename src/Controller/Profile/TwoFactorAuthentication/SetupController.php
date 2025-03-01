<?php

declare(strict_types=1);

namespace App\Controller\Profile\TwoFactorAuthentication;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class SetupController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TotpAuthenticatorInterface $totpAuthenticator,
    ) {}

    #[Route('/profile/2fa/setup', name: 'app_profile_2fa_setup', methods: ['GET', 'POST'])]
    public function __invoke(#[CurrentUser] User $user, Request $request): Response
    {
        if ($user->isTotpAuthenticationEnabled()) {
            return $this->redirectToRoute('app_profile');
        }

        if ($request->isMethod('POST')) {
            if ($this->totpAuthenticator->checkCode($user, $request->request->getString('code'))) {
                $user->setTwoFactorsAuthenticationTotpEnabled(true);
                $this->entityManager->flush();

                $this->addFlash('error', '2FA enabled!');

                return $this->redirectToRoute('app_profile');
            }

            $this->addFlash('error', 'Invalid code');

            return $this->redirectToRoute('app_profile_2fa_setup');
        }

        $secret = $user->getTwoFactorsAuthenticationTotpSecret();
        if (null === $secret) {
            $secret = $this->totpAuthenticator->generateSecret();
            $user->setTwoFactorsAuthenticationTotpSecret($secret);
            $this->entityManager->flush();
        }

        return $this->render('profile/two_factor_authentication/setup.html.twig', [
            'secret' => $secret,
        ]);
    }
}
