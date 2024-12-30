<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\NotificationController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[CoversClass(NotificationController::class)]
final class NotificationControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use InteractsWithMessenger;
    use ResetDatabase;

    public function testShouldRenderHomePage(): void
    {
        $user = UserFactory::createOne();

        $this->transport()->queue()->assertEmpty();

        $this->browser()
            ->actingAs($user)
            ->visit('/notification')
            ->assertSuccessful();

        $this->transport()->queue()->assertCount(1);
    }
}
