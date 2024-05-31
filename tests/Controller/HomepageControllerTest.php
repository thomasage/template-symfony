<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\HomepageController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(HomepageController::class)]
final class HomepageControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }
}
