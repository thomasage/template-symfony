<?php

declare(strict_types=1);

use App\Repository\ResetPasswordRequestRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('symfonycasts_reset_password', [
        'request_password_repository' => ResetPasswordRequestRepository::class,
    ]);
};
