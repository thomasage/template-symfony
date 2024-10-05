<?php

declare(strict_types=1);

use App\Entity\User;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'password_hashers' => [
            PasswordAuthenticatedUserInterface::class => 'auto',
        ],
        'providers' => [
            'app_user_provider' => [
                'entity' => [
                    'class' => User::class,
                    'property' => 'email',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'app_user_provider',
                'form_login' => [
                    'login_path' => 'app_login',
                    'check_path' => 'app_login',
                    'enable_csrf' => true,
                ],
                'two_factor' => [
                    'auth_form_path' => '2fa_login',
                    'check_path' => '2fa_login_check',
                ],
                'logout' => [
                    'path' => 'app_logout',
                ],
            ],
        ],
        'access_control' => [
            [
                'path' => '/login',
                'roles' => 'PUBLIC_ACCESS',
            ],
            [
                'path' => '/logout',
                'roles' => 'PUBLIC_ACCESS',
            ],
            [
                'path' => '^/2fa',
                'role' => 'IS_AUTHENTICATED_2FA_IN_PROGRESS',
            ],
            [
                'path' => '^/',
                'roles' => 'ROLE_USER',
            ],
        ],
    ]);
    if ('test' === $containerConfigurator->env()) {
        $containerConfigurator->extension('security', [
            'password_hashers' => [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4,
                    'time_cost' => 3,
                    'memory_cost' => 10,
                ],
            ],
        ]);
    }
};
