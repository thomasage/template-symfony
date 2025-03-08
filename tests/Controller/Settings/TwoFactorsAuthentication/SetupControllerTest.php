<?php

declare(strict_types=1);

namespace App\Tests\Controller\Settings\TwoFactorsAuthentication;

use App\Controller\Settings\TwoFactorsAuthentication\SetupController;
use App\Factory\UserFactory;
use App\Tests\Double\FakeTotpAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(SetupController::class)]
final class SetupControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldSetupTwoFactorsAuthentication(): void
    {
        $user = UserFactory::new()->create();

        $this->browser()
            ->actingAs($user)
            ->visit('/settings/2fa/setup')
            ->assertSuccessful()
            ->assertSeeElement('form[name="two_factors_authentication_confirmation"]')
            ->assertSeeElement('#two_factors_authentication_confirmation_code')
            ->fillField('two_factors_authentication_confirmation_code', FakeTotpAuthenticator::VALID_CODE)
            ->click('button[type=submit]')
            ->assertOn('/settings/profile');

        self::assertTrue($user->isTotpAuthenticationEnabled());
    }

    public function testShouldRedirectWhenTotpAuthenticationIsAlreadyEnabled(): void
    {
        $user = UserFactory::new()->create([
            'twoFactorsAuthenticationTotpSecret' => 'test',
        ]);
        $user->enableTwoFactorsAuthenticationTotp();
        $user->_save();

        $this->browser()
            ->actingAs($user)
            ->interceptRedirects()
            ->visit('/settings/2fa/setup')
            ->assertRedirectedTo('/settings/profile');

        self::assertTrue($user->isTotpAuthenticationEnabled());
    }

    public function testShouldRenderProfileFormWhenCodeIsInvalid(): void
    {
        $user = UserFactory::new()->create();

        $translator = self::getContainer()->get(TranslatorInterface::class);
        \assert($translator instanceof TranslatorInterface);

        $this->browser()
            ->actingAs($user)
            ->visit('/settings/2fa/setup')
            ->assertSuccessful()
            ->assertSeeElement('form[name="two_factors_authentication_confirmation"]')
            ->assertSeeElement('#two_factors_authentication_confirmation_code')
            ->fillField('two_factors_authentication_confirmation_code', '000000')
            ->click('button[type=submit]')
            ->assertOn('/settings/2fa/setup')
            ->assertSee($translator->trans('notifications.2fa_code_invalid', domain: 'settings'));

        self::assertFalse($user->isTotpAuthenticationEnabled());
    }
}
