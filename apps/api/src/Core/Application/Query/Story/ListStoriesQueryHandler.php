<?php

declare(strict_types=1);

namespace Core\Application\Query\Story;

use Core\Domain\Story\StoryRepository;

use function array_map;

class ListStoriesQueryHandler
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
