<?php

declare(strict_types=1);

namespace App\Tests\Controller\Security\ResetPassword;

use App\Controller\Security\ResetPassword\RequestController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[CoversClass(RequestController::class)]
final class RequestControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use InteractsWithMessenger;
    use ResetDatabase;

    public function testShouldSendMessageToQueue(): void
    {
        $user = UserFactory::createOne();

        $this->transport()->queue()->assertEmpty();

        $this->browser()
            ->actingAs($user)
            ->visit('/reset-password')
            ->assertSuccessful()
            ->fillField('reset_password_request_form_email', $user->getEmail())
            ->click('button')
            ->assertOn('/reset-password/check-email');

        $this->transport()->queue()->assertCount(1);
    }
}
