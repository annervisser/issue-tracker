<?php

declare(strict_types=1);

namespace Core\Application\Query\State;

use Core\Domain\State\StateRepository;

class GetStateQueryHandler
{
    public function __construct(
        private readonly StateRepository $stateRepository
    ) {
    }

    public function __invoke(GetStateQuery $query): StateResponseDTO|null
    {
        $state = $this->stateRepository->find($query->stateId);

        if (! $state) {
            return null;
        }

        return StateResponseDTO::fromEntity($state);
    }
}
