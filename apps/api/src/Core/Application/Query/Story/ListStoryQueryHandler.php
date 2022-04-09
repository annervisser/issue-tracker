<?php

namespace Core\Application\Query\Story;

use Core\Domain\Story\StoryRepository;

use function array_map;

class ListStoryQueryHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository
    ) {
    }

    /** @return list<StoryResponseDTO> */
    public function __invoke(): array
    {
        $stories = $this->storyRepository->list();

        return array_map(StoryResponseDTO::fromEntity(...), $stories);
    }
}
