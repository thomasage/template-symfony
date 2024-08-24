<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\SecurityController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(SecurityController::class)]
final class SecurityControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    public function testUserCantLoginWithInvalidEmailAddress(): void
    {
        $client = self::createClient();
        $client->request('GET', '/login');
        $client->submitForm('Sign in', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);
        self::assertResponseRedirects('/login');
    }

    public function testUserCanLogin(): void
    {
        $client = self::createClient();

        UserFactory::createOne(['email' => 'user@example.com', 'password' => 'password']);

        $client->request('GET', '/login');
        $client->submitForm('Sign in', [
            '_username' => 'user@example.com',
            '_password' => 'password',
        ]);
        self::assertResponseRedirects('/');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}
