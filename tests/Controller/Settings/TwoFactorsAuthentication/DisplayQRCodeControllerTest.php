<?php

declare(strict_types=1);

namespace App\Tests\Controller\Settings\TwoFactorsAuthentication;

use App\Controller\Settings\TwoFactorsAuthentication\DisplayQRCodeController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(DisplayQRCodeController::class)]
final class DisplayQRCodeControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldRenderQRCode(): void
    {
        $user = UserFactory::new()->create();

        $this->browser()
            ->actingAs($user)
            ->visit('/settings/2fa/qrcode')
            ->assertSuccessful()
            ->assertHeaderContains('Content-Type', 'image/png');
    }
}
