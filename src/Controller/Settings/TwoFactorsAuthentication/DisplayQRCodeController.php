<?php

declare(strict_types=1);

namespace App\Controller\Settings\TwoFactorsAuthentication;

use App\Entity\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class DisplayQRCodeController extends AbstractController
{
    public function __construct(private readonly TotpAuthenticatorInterface $totpAuthenticator) {}

    #[Route('/settings/2fa/qrcode', name: 'app_settings_2fa_qrcode', methods: ['GET'])]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $totpQrCode = $this->totpAuthenticator->getQRContent($user);

        $result = new Builder(
            writer: new PngWriter(),
            data: $totpQrCode,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 200,
            margin: 0,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        return new Response($result->build()->getString(), headers: [
            'Content-Type' => 'image/png',
        ]);
    }
}
