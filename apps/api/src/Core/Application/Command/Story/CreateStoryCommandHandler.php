<?php

namespace Core\Application\Command\Story;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Core\Domain\Story\StoryTitle;
use Ramsey\Uuid\UuidInterface;

final class CreateStoryCommandHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository
    ) {
    }

    public function __invoke(CreateStoryCommand $command): UuidInterface
    {
        $story = Story::create(new StoryTitle($command->title));
        $this->storyRepository->create($story);

        return $story->getId();
    }
}
