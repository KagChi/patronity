<?php

return (new PhpCsFixer\Config())
    ->setRules([
        'blank_line_before_statement' => ['statements' => ['return']],
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'same',
            'position_after_control_structures' => 'same',
        ],
        'no_unused_imports' => true,
        'single_quote' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->in(__DIR__ . "/app")
            ->in(__DIR__ . "/routes")
    );
