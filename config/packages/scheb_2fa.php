<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('scheb_two_factor', [
        'security_tokens' => [
            UsernamePasswordToken::class,
            PostAuthenticationToken::class,
        ],
        'email' => [
            'digits' => 6,
            'enabled' => true,
            'sender_email' => 'no-reply@example.com',
        ],
    ]);
};
