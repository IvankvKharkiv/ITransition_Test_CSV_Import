<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bin', 'config', 'public', 'var', 'vendor']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'phpdoc_align' => ['align' => 'left'],
        'yoda_style' => false,
        'single_line_comment_style' => false,
        'return_assignment' => false,
        'no_superfluous_phpdoc_tags' => false,
        'increment_style' => ['style' => 'post'],
        'declare_strict_types' => true,
        'concat_space' => ['spacing' => 'one'],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'method_public_abstract',
                'method_protected_abstract',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'multiline_whitespace_before_semicolons' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
        ],
        'logical_operators' => true,
        'strict_comparison' => true,
    ])
    ->setFinder($finder);