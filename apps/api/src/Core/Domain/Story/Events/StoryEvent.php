<?php

declare(strict_types=1);

namespace Core\Domain\Story\Events;

use Core\Domain\Story\Story;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\DomainEvent;

/** @psalm-immutable */
abstract class StoryEvent extends DomainEvent
{
    private readonly DateTimeInterface $dateTime;

    public function __construct(
        private readonly Story $story,
    ) {
        $this->dateTime = new DateTimeImmutable();
    }

    public function getStoryId(): UuidInterface
    {
        return $this->story->getId();
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }
}
