<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Shared\Infra\Settings\SettingsInterface;
use Slim\App;

// phpcs:disable SlevomatCodingStandard.Functions.StaticClosure.ClosureNotStatic -- router requires non-static callbacks
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
    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        // TODO do this properly, instead of allowing everything
        return $handler->handle($request)
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', '*')
            ->withHeader('Access-Control-Allow-Methods', '*');
    });
};
