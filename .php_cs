<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php');

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'no_unused_imports' => true,
        'native_function_invocation' => true,
        'is_null' => true,
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'mb_str_functions' => true,
        'modernize_types_casting' => true,
        'native_constant_invocation' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
    ])
    ->setFinder($finder);
