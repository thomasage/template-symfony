<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\Framework\Messenger\TransportConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container): void {
    $messenger = $framework->messenger();

    /** @var TransportConfig $transport */
    $transport = $messenger->transport('async');
    $transport->dsn('%env(MESSENGER_TRANSPORT_DSN)%');

    if ('test' === $container->env()) {
        $transport->dsn('test://');
    }
};
