<?php

declare(strict_types=1);

$directory = new RecursiveDirectoryIterator(__DIR__ . '/../src');
$fullTree  = new RecursiveIteratorIterator($directory);
/** @var array<mixed, array{string}> $files */
$files = new RegexIterator($fullTree, '/.+(.+\.php$)/i', RegexIterator::GET_MATCH);

/** @noinspection PreloadingUsageCorrectnessInspection */
include __DIR__ . '/bootstrap.php';
foreach ($files as $file) {
    opcache_compile_file($file[0]);
}
