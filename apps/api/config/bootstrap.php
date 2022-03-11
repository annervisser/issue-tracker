<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Shared\Infra\Settings\Settings;
use Shared\Infra\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

$containerBuilder = new ContainerBuilder();

// Set up settings
$config      = require __DIR__ . '/config.php';
$settings    = new Settings($config);
$localConfig = __DIR__ . '/config.local.php';
if (file_exists($localConfig)) {
    $settingsArray = (array) require $localConfig;
    Assert::isMap($settingsArray);
    $settings = $settings->addSettings($settingsArray);
}

$containerBuilder->addDefinitions([SettingsInterface::class => $settings]);

// Set up container compilation
if ($settings->get('container.enableCompilation') !== false) {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up dependencies
foreach (glob(__DIR__ . '/dependencies/*.php') ?: [] as $dependencyFile) {
    /** @psalm-suppress UnresolvableInclude */
    $dependency = require $dependencyFile;
    assert(is_callable($dependency));
    ($dependency)($containerBuilder);
}

return $containerBuilder->build();
