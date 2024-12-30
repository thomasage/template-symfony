<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(UserFactory::class)]
final class UserFactoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    public function testShouldCreateUser(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'test@test.com',
        ]);

        self::assertSame('test@test.com', $user->getEmail());
    }
}
