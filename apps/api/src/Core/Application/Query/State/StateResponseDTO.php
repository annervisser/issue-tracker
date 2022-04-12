<?php

declare(strict_types=1);

namespace Core\Application\Query\State;

use Core\Domain\State\State;

class StateResponseDTO
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {
    }

    public static function fromEntity(State $story): self
    {
        return new self(
            $story->getId()->toString(),
            $story->getName()->getName(),
        );
    }
}
