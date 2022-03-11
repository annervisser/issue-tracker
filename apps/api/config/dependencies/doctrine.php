<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type as DBALTypes;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Shared\Infra\Doctrine\DoctrineTrackingPolicySubscriber;
use Shared\Infra\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

return static function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        EntityManagerInterface::class => DI\get(EntityManager::class),
        EntityManager::class => static function (ContainerInterface $c): EntityManager {
            $settings = $c->get(SettingsInterface::class);
            assert($settings instanceof SettingsInterface);

            $config = Setup::createAnnotationMetadataConfiguration(
                (array) $settings->get('doctrine.metadata_dirs'),
                (bool) $settings->get('doctrine.dev_mode')
            );

            $metaDataDirs = (array) $settings->get('doctrine.metadata_dirs');
            Assert::allString($metaDataDirs);

            $config->setMetadataDriverImpl(
                new AttributeDriver($metaDataDirs)
            );

            // TODO add explicit cache for metadata & query

            $connectionSettings = (array) $settings->get('doctrine.connection');
            Assert::isMap($connectionSettings);

            $entityManager = EntityManager::create(
                $connectionSettings,
                $config
            );

            $eventManager = $entityManager->getEventManager();
            $eventManager->addEventSubscriber(new DoctrineTrackingPolicySubscriber());

            DBALTypes::addType('uuid_binary_ordered_time', UuidBinaryOrderedTimeType::class);
            $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(
                'uuid_binary_ordered_time',
                'binary'
            );

            return $entityManager;
        },
    ]);
};
