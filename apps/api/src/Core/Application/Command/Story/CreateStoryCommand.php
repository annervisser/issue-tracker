<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Ramsey\Uuid\UuidInterface;

final class CreateStoryCommand
{
    public function __construct(
        public readonly string $title,
        public readonly UuidInterface $stateId,
    ) {
    }
}
