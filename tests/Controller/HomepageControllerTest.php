<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\HomepageController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(HomepageController::class)]
final class HomepageControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    public function testHomepageIsNotAccessibleToGuest(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertResponseRedirects('/login');
    }
}
