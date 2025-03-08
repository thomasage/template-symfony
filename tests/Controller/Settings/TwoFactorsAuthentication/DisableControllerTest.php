<?php

declare(strict_types=1);

namespace App\Tests\Controller\Settings\TwoFactorsAuthentication;

use App\Controller\Settings\TwoFactorsAuthentication\DisableController;
use App\Factory\UserFactory;
use App\Tests\Double\FakeTotpAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(DisableController::class)]
final class DisableControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldRenderForm(): void
    {
        $user = UserFactory::new()->create([
            'twoFactorsAuthenticationTotpSecret' => 'test',
        ]);
        $user->enableTwoFactorsAuthenticationTotp();
        $user->_save();

        $this->browser()
            ->actingAs($user)
            ->visit('/settings/2fa/disable')
            ->assertSuccessful()
            ->assertSeeElement('form[name="two_factors_authentication_confirmation"]')
            ->assertSeeElement('#two_factors_authentication_confirmation_code')
            ->fillField('two_factors_authentication_confirmation_code', FakeTotpAuthenticator::VALID_CODE)
            ->click('button[type=submit]')
            ->assertOn('/settings/profile');

        self::assertFalse($user->isTotpAuthenticationEnabled());
    }

    public function testShouldRedirectWhenAuthenticationIsAlreadyDisabled(): void
    {
        $user = UserFactory::new()->create();

        $this->browser()
            ->actingAs($user)
            ->interceptRedirects()
            ->visit('/settings/2fa/disable')
            ->assertRedirectedTo('/settings/profile');

        self::assertFalse($user->isTotpAuthenticationEnabled());
    }
}
