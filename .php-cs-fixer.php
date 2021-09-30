<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.DIRECTORY_SEPARATOR.'src')
;

$config = new PhpCsFixer\Config();

return $config
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
            'imports_order' => null,
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_order' => true,
        'yoda_style' => [
            'equal' => null,
            'identical' => null,
            'less_and_greater' => null,
            'always_move_variable' => false,
        ],
        // risky -->
        'strict_param' => true,
    ])
    ->setFinder($finder)
;
