<?php

declare(strict_types=1);

use Core\Domain\State\StateRepository;
use Core\Domain\Story\StoryRepository;
use Core\Infra\Repository\DoctrineStateRepository;
use Core\Infra\Repository\DoctrineStoryRepository;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

function getEm(ContainerInterface $c): EntityManagerInterface
{
    $em = $c->get(EntityManagerInterface::class);
    assert($em instanceof EntityManagerInterface);

    return $em;
}

return static function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        StoryRepository::class => static fn (ContainerInterface $c) => new DoctrineStoryRepository(getEm($c)),
        StateRepository::class => static fn (ContainerInterface $c) => new DoctrineStateRepository(getEm($c)),
    ]);
};
