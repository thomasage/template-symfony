<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        UserFactory::createOne([
            'email' => 'admin@example.com',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN'],
        ]);

        UserFactory::createMany(20);
    }
}
