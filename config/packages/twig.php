<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig, ContainerConfigurator $container): void {
    $twig->fileNamePattern('*.twig');

    if ('test' === $container->env()) {
        $twig->strictVariables(true);
    }
};
