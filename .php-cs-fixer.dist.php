<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;
// Directories to check
$includedPatterns = [
    __DIR__ . '/src',
    __DIR__ . '/tests',
];

$rules = [
    '@Symfony' => true,
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'declare_strict_types' => true,
    'no_superfluous_phpdoc_tags' => true,
    'php_unit_fqcn_annotation' => false,
    'phpdoc_to_comment' => false,
    'yoda_style' => false,
    'native_function_invocation' => [
        'include' => ['@compiler_optimized'],
        'scope' => 'namespaced',
        'strict' => true,
    ],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'trailing_comma_in_multiline' => true,
    'phpdoc_scalar' => true,
    'unary_operator_spaces' => true,
    'binary_operator_spaces' => true,
    'blank_line_before_statement' => [
        'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
    ],
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_var_without_name' => true,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => true,
    ]
];

$finder = PhpCsFixer\Finder::create()
    ->in($includedPatterns)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

// Config initialization
$config = new PhpCsFixer\Config();
$config->setRules($rules)->setFinder($finder);

return $config;
