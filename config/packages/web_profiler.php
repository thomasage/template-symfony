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
        $webProfiler
            ->interceptRedirects(false)
            ->toolbar(true);
        $profiler
            ->collectSerializerData(true)
            ->onlyExceptions(false);
    }

    if ('test' === $container->env()) {
        $webProfiler
            ->interceptRedirects(false)
            ->toolbar(false);
        $profiler
            ->collect(false);
    }
};
