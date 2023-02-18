<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->parallel();
    $config->paths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/public',
    ]);
    $config->skip([
        // skip paths with legacy code
        __DIR__ . '/public/build',
        __DIR__ . '/vendor',
        __DIR__ . '/config/bundles.php',
        __DIR__ . '/ecs.php',
        __DIR__ . '/phpstan',
        __DIR__ . '/tests/bootstrap.php',
        __DIR__ . '/tests/console-application.php',
        __DIR__ . '/tests/object-manager.php',
    ]);
    $config->ruleWithConfiguration(ArraySyntaxFixer::class, ['syntax' => 'short']);
    $config->ruleWithConfiguration(MethodArgumentSpaceFixer::class, ['on_multiline' => 'ensure_fully_multiline']);

    $config->sets([
        SetList::ARRAY,
        SetList::CONTROL_STRUCTURES,
        SetList::CLEAN_CODE,
        SetList::COMMON,
        SetList::COMMENTS,
        SetList::DOCBLOCK,
        SetList::DOCTRINE_ANNOTATIONS,
        SetList::NAMESPACES,
        SetList::PSR_12,
        SetList::PHPUNIT,
        SetList::STRICT,
        SetList::SPACES,
        SetList::SYMPLIFY,
    ]);
};
