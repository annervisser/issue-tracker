<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Shared\Infra\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

return static function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => static function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            assert($settings instanceof SettingsInterface);

            $logger = new Logger((string) $settings->get('logger.name'));
            $logger->pushProcessor(new UidProcessor());

            $logLevel = (string) $settings->get('logger.level');
            Assert::oneOf($logLevel, array_keys(Logger::getLevels()));

            $handler = new StreamHandler((string) $settings->get('logger.path'), $logLevel);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
