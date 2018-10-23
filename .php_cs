<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.DIRECTORY_SEPARATOR.'src')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP71Migration' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_superfluous_elseif' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'importsOrder' => null,
            'sortAlgorithm' => 'alpha',
        ],
        'phpdoc_order' => true,
        'yoda_style' => null,
        // risky -->
        'strict_param' => true,
    ])
    ->setFinder($finder)
;
