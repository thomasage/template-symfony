<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use LogicException;

#[CoversClass(SecurityController::class)]
final class SecurityControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldRenderLoginForm(): void
    {
        $this->browser()
            ->visit('/login')
            ->assertSuccessful();
    }

    public function testShouldRedirectAuthenticatedUser(): void
    {
        $user = UserFactory::createOne();

        $this->browser()
            ->actingAs($user)
            ->interceptRedirects()
            ->visit('/login')
            ->assertRedirectedTo('/');
    }

    public function testShouldThrowExceptionWhenLogoutMethodIsCalledDirectly(): never
    {
        $this->expectException(LogicException::class);

        $controller = new SecurityController();
        $controller->logout();
    }
}
