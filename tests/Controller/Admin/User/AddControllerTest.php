<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin\User;

use App\Controller\Admin\User\AddController;
use App\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(AddController::class)]
final class AddControllerTest extends KernelTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public function testShouldAddAnUser(): void
    {
        $user = UserFactory::new()->admin()->create();

        $this->browser()
            ->actingAs($user)
            ->visit('/admin/user/add')
            ->assertSuccessful()
            ->assertSeeElement('form[name="user_add"]')
            ->assertSeeElement('#user_add_email')
            ->assertSeeElement('#user_add_password_first')
            ->assertSeeElement('#user_add_password_second')
            ->fillField('user_add_email', 'test@example.com')
            ->fillField('user_add_password_first', 'CYqZy`>8U#g6~)V}D:={zP')
            ->fillField('user_add_password_second', 'CYqZy`>8U#g6~)V}D:={zP')
            ->click('button[type=submit]')
            ->assertOn('/admin/user');

        UserFactory::repository()->assert()->count(2);
    }
}
