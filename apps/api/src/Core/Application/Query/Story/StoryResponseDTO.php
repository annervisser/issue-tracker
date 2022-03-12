<?php

namespace Core\Application\Query\Story;

use Core\Domain\Story\Story;
use DateTimeInterface;

class StoryResponseDTO
{
    private function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $createdAt,
    ) {
    }

    public static function fromEntity(Story $story): self
    {
        return new self(
            $story->getId()->toString(),
            $story->getTitle()->getTitle(),
            $story->getCreatedAt()->format(DateTimeInterface::ATOM)
        );
    }
}
