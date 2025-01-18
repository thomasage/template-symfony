<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
    )
    ->withPhpSets(
        php84: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
    )
    ->withSets([
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ])
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class => __DIR__ . '/src/Entity/Constructor.php',
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        TypedPropertyFromStrictConstructorRector::class,
    ]);
