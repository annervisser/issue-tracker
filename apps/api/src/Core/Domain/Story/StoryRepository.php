<?php

declare(strict_types=1);

namespace Core\Domain\Story;

use Ramsey\Uuid\UuidInterface;

interface StoryRepository
{
    public function create(Story $story): void;

    public function find(UuidInterface $id): Story|null;

    public function delete(UuidInterface $id): void;

    /** @return list<Story> */
    public function list(): array;

    /** @return list<Story> */
    public function inState(UuidInterface $stateId): array;
}
