<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Shared\Domain\Assert;

final class ReorderStoryCommandHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository,
    ) {
    }

    public function __invoke(ReorderStoryCommand $command): void
    {
        $story = $this->storyRepository->find($command->storyId);
        Assert::notNull($story);

        $this->updateOrdering($story, $command);

        $this->storyRepository->update($story);
    }

    private function updateOrdering(Story $story, ReorderStoryCommand $command): void
    {
        if ($command->afterStoryId === null) {
            $story->setOrdering(0);

            return;
        }

        $afterStory = $this->storyRepository->find($command->afterStoryId);
        Assert::notNull($afterStory);

        Assert::eq($story->getState(), $afterStory->getState(), 'Cannot reorder across states');

        $story->setOrdering($afterStory->getOrdering() + 1);
    }
}
