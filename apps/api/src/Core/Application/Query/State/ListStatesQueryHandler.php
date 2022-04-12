<?php

declare(strict_types=1);

namespace Core\Application\Query\State;

use Core\Domain\State\StateRepository;

use function array_map;

class ListStatesQueryHandler
{
    public function __construct(
        private readonly StateRepository $stateRepository
    ) {
    }

    /** @return list<StateResponseDTO> */
    public function __invoke(): array
    {
        $stories = $this->stateRepository->list();

        return array_map(StateResponseDTO::fromEntity(...), $stories);
    }
}
