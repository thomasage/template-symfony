<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\DashboardController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(DashboardController::class)]
final class DashboardControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    #[Test]
    public function shouldDisplayDashboard(): void
    {
        $client = self::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user->_real());
        $client->request('GET', '/admin');

        self::assertResponseIsSuccessful();
    }
}
