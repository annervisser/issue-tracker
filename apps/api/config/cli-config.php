<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$container = require __DIR__ . '/bootstrap.php';
/** @psalm-suppress RedundantConditionGivenDocblockType psalm might be smart, but not all tools are */
assert($container instanceof Container);

$entityManager = $container->get(EntityManager::class);
assert($entityManager instanceof EntityManager);

return ConsoleRunner::createHelperSet($entityManager);
