<?php

declare(strict_types=1);

namespace Core\Application\Query\Story;

use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Assert;

class ListStoriesInStateQuery
{
    public function __construct(
        public readonly UuidInterface $stateId
    ) {
        Assert::uuidV1($this->stateId);
    }
}
