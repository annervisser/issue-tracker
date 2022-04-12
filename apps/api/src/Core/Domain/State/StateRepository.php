<?php

declare(strict_types=1);

namespace Core\Domain\State;

use Ramsey\Uuid\UuidInterface;

interface StateRepository
{
    public function create(State $state): void;

    public function find(UuidInterface $id): State|null;

    /** @return list<State> */
    public function list(): array;
}
