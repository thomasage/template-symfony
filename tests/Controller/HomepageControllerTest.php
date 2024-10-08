<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\HomepageController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(HomepageController::class)]
final class HomepageControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    #[Test]
    public function homepageIsNotAccessibleToGuest(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');

        self::assertResponseRedirects('/login');
    }

    #[Test]
    public function homepageIsAccessibleToAuthenticatedUser(): void
    {
        $client = self::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user->_real());
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }
}
