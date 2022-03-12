<?php

declare(strict_types=1);

namespace Shared\Domain;

use Ramsey\Uuid\UuidInterface;

abstract class Aggregate implements EntityInterface
{
    /** @var list<DomainEventInterface> */
    private array $events = [];

    abstract public function getId(): UuidInterface;

    /** @return list<DomainEventInterface> */
    public function popEvents(): array
    {
        $events       = $this->events;
        $this->events = [];

        return $events;
    }

    protected function raise(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }
}
