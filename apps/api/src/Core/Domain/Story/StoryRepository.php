<?php

namespace Core\Domain\Story;

use Ramsey\Uuid\UuidInterface;

interface StoryRepository
{
    public function create(Story $story): void;

    public function find(UuidInterface $id): Story|null;
}
