<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Shared\Infra\Settings\SettingsInterface;
use Slim\App;

return static function (App $app): void {
    $settings = $app->getContainer()?->get(SettingsInterface::class);
    assert($settings instanceof SettingsInterface);

    $logger = $app->getContainer()?->get(LoggerInterface::class);
    assert($logger instanceof LoggerInterface);

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware(
        (bool) $settings->get('displayErrorDetails'),
        (bool) $settings->get('logError'),
        (bool) $settings->get('logErrorDetails'),
        $logger,
    );
};
