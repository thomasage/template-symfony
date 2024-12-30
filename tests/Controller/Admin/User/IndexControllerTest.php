<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin\User;

use App\Controller\Admin\User\IndexController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(IndexController::class)]
final class IndexControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldRenderHomePage(): void
    {
        $user = UserFactory::new()->admin()->create();

        $this->browser()
            ->actingAs($user)
            ->visit('/admin/user')
            ->assertSuccessful()
            ->assertSee($user->getEmail());
    }
}
