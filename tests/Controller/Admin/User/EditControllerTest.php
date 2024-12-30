<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin\User;

use App\Controller\Admin\User\EditController;
use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(EditController::class)]
final class EditControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldUpdateAnUser(): void
    {
        $user = UserFactory::new()->admin()->create();

        $userToUpdate = UserFactory::createOne([
            'email' => 'test@example.com',
        ]);

        $this->browser()
            ->actingAs($user)
            ->visit('/admin/user/' . $userToUpdate->getUuid() . '/edit')
            ->assertSuccessful()
            ->assertSeeElement('form[name="user_edit"]')
            ->assertSeeElement('#user_edit_email')
            ->fillField('user_edit_email', 'example@example.com')
            ->click('button[type=submit]')
            ->assertOn('/admin/user');

        $userToUpdate = UserFactory::repository()->find($userToUpdate->getId());
        \assert($userToUpdate instanceof User);

        self::assertSame('example@example.com', $userToUpdate->getEmail());
    }
}
