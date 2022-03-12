<?php

declare(strict_types=1);

use Core\Domain\Story\StoryRepository;
use Core\Infra\Repository\DoctrineStoryRepository;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return static function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        StoryRepository::class => static function (ContainerInterface $c) {
            $em = $c->get(EntityManagerInterface::class);
            assert($em instanceof EntityManagerInterface);

            return new DoctrineStoryRepository($em);
        },
    ]);
};
