<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\Framework\RouterConfig;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $containerConfigurator, FrameworkConfig $framework): void {
    $router = $framework->router();
    assert($router instanceof RouterConfig);

    $router->defaultUri('%env(APP_URL)%');
    if ('prod' === $containerConfigurator->env()) {
        $router->strictRequirements(null);
    }
};
