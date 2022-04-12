<?php

declare(strict_types=1);

namespace Core\Application\Query\Story;

use Core\Domain\Story\StoryRepository;

use function array_map;

class ListStoriesInStateQueryHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository
    ) {
    }

    /** @return list<StoryResponseDTO> */
    public function __invoke(ListStoriesInStateQuery $query): array
    {
        $stories = $this->storyRepository->inState($query->stateId);

        return array_map(StoryResponseDTO::fromEntity(...), $stories);
    }
}
