<?php

declare(strict_types=1);

namespace App\Tests\Controller\Settings;

use App\Controller\Settings\ProfileController;
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
            ->visit('/settings/profile')
            ->assertSuccessful()
            ->assertSeeElement('form[name="profile"]')
            ->assertSeeElement('#profile_email')
            ->fillField('profile_email', 'test@example.com')
            ->click('button[type=submit]')
            ->assertOn('/settings/profile');

        self::assertSame('test@example.com', $user->getEmail());
    }
}
