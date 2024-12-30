<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\ProfileController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(ProfileController::class)]
final class ProfileControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldRenderProfileForm(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'example@example.com',
        ]);

        $this->browser()
            ->actingAs($user)
            ->visit('/profile')
            ->assertSuccessful()
            ->assertSeeElement('form[name="profile"]')
            ->assertSeeElement('#profile_email')
            ->assertSeeElement('#profile_twoFactorsAuthentication')
            ->fillField('profile_email', 'test@example.com')
            ->checkField('profile_twoFactorsAuthentication')
            ->click('button[type=submit]')
            ->assertOn('/profile');

        self::assertSame('test@example.com', $user->getEmail());
        self::assertTrue($user->hasTwoFactorsAuthentication());
    }
}
