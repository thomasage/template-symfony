<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use App\Factory\UserFactory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(UserCrudController::class)]
final class UserCrudControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    #[Test]
    public function shouldDisplayIndex(): void
    {
        $client = self::createClient();

        $users = UserFactory::createMany(5);

        $urlGenerator = self::getContainer()->get(AdminUrlGenerator::class);
        assert($urlGenerator instanceof AdminUrlGenerator);

        $url = $urlGenerator
            ->setController(UserCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        $client->loginUser($users[0]->_real());
        $client->request('GET', $url);

        self::assertSame(User::class, UserCrudController::getEntityFqcn());
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(5, 'tbody [data-column="email"]');
    }
}
