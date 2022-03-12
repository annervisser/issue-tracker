<?php

declare(strict_types=1);

namespace Shared\Domain;

use DateTimeInterface;

/** @psalm-immutable */
abstract class DomainEvent implements DomainEventInterface
{
    abstract public function getDateTime(): DateTimeInterface;
}
