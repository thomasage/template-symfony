<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\Framework\ProfilerConfig;
use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (
    FrameworkConfig $framework,
    WebProfilerConfig $webProfiler,
    ContainerConfigurator $container,
): void {
    $profiler = $framework->profiler();
    assert($profiler instanceof ProfilerConfig);

    if ('dev' === $container->env()) {
        $webProfiler->toolbar([]);
        $profiler->collectSerializerData(true);
    }

    if ('test' === $container->env()) {
        $profiler
            ->collect(false)
            ->collectSerializerData(true);
    }
};
