<?php

declare(strict_types=1);

namespace Core\Application\Query\State;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Assert;

class GetStateQuery
{
    public function __construct(
        public readonly UuidInterface $stateId
    ) {
        Assert::uuidV1($this->stateId);
    }

    public static function fromString(string $id): self
    {
        Assert::uuid($id);

        return new self(Uuid::fromString($id));
    }
}
