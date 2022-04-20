<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Ramsey\Uuid\UuidInterface;

final class ReorderStoryCommand
{
    public function __construct(
        public readonly UuidInterface $storyId,
        public readonly UuidInterface|null $afterStoryId,
    ) {
    }
}
