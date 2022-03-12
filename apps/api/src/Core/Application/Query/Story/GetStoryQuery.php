<?php

declare(strict_types=1);

namespace Core\Application\Query\Story;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Assert;

class GetStoryQuery
{
    public function __construct(
        public readonly UuidInterface $storyId
    ) {
        Assert::uuidV1($this->storyId);
    }

    public static function fromString(string $id): self
    {
        Assert::uuid($id);

        return new self(Uuid::fromString($id));
    }
}
