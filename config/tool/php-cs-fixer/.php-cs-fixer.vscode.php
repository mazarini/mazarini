<?php

$projectRoot = dirname(__DIR__, 3);

if (!file_exists($projectRoot.'/src')) {
    echo '"',$projectRoot,'/src" don\'t exist!',"\n";
    echo 'Verify "$projectRoot" in config/tool/phpcs/.php-cs-fixer.dist.php',"\n";
    echo exit(1);
}

$fixer = (new PhpCsFixer\Config())
    ->setRules([
        '@PHP81Migration' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'protected_to_private' => false,
        'native_constant_invocation' => ['strict' => false],
        'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => false],
        'header_comment' => (isset($fileHeaderComment)) ? ['header' => $fileHeaderComment] : [],
        'modernize_strpos' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        (new PhpCsFixer\Finder())
        ->in($projectRoot.'/lib')
        ->in($projectRoot.'/src')
        ->in($projectRoot.'/tests')
        ->notPath('#/Fixtures/#')
        ->notPath('#/Fixtures/#')
        ->notPath('#/lib/var/#')
        ->notPath('#/lib/config/#')
        ->notPath('#/lib/public/#')
        ->notPath('#/lib/bin/#')
        ->exclude([])
    )
    ->setCacheFile($projectRoot.'/var/cache/.php-cs-fixer.cache')
;

return $fixer;
