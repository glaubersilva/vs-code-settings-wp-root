<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'build',
        'dist',
        'wp-content/uploads',
        'wp-content/cache',
        'wp-content/backup-db',
        'wp-content/backups',
        'wp-content/blogs.dir',
        'wp-content/upgrade',
        'wp-content/uploads',
        'wp-content/wp-cache-config.php',
        'wp-content/plugins/hello.php'
    ])
    ->notPath('includes')
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        // PSR-12 Rules
        '@PSR12' => true,

        // Import Organization Rules
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        'no_unused_imports' => true,
        'single_import_per_statement' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'single_line_after_imports' => true,

        // Array formatting rules
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'whitespace_after_comma_in_array' => true,
        'trim_array_spaces' => true,

        // Indentation and spacing rules
        'indentation_type' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'throw',
                'use',
                'parenthesis_brace_block',
                'square_brace_block',
            ]
        ],
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_whitespace_in_blank_line' => true,

        // Operator spacing
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'single_space',
                '=' => 'single_space',
            ]
        ],
        'object_operator_without_whitespace' => true,
        'unary_operator_spaces' => true,
        'ternary_operator_spaces' => true,

        // Other formatting rules
        'concat_space' => ['spacing' => 'one'],
        'standardize_not_equals' => true,
        'space_after_semicolon' => true,
    ])
    ->setLineEnding("\n")
    ->setFinder($finder);
