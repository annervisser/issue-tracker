<?php

namespace Core\Application\Query\Story;

use Core\Domain\Story\StoryRepository;

class GetStoryQueryHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository
    ) {
    }

    public function __invoke(GetStoryQuery $query): StoryResponseDTO|null
    {
        $story = $this->storyRepository->find($query->storyId);

        if (! $story) {
            return null;
        }

        return StoryResponseDTO::fromEntity($story);
    }
}
