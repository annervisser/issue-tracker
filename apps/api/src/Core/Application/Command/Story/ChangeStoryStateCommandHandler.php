<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Core\Domain\State\StateRepository;
use Core\Domain\Story\StoryRepository;
use Shared\Domain\Assert;

final class ChangeStoryStateCommandHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository,
        private readonly StateRepository $stateRepository,
    ) {
    }

    public function __invoke(ChangeStoryStateCommand $command): void
    {
        $story = $this->storyRepository->find($command->storyId);
        Assert::notNull($story);

        $newState = $this->stateRepository->find($command->newStateId);
        Assert::notNull($newState);

        Assert::notEq($story->getState(), $newState, 'Story is already in this state');

        $story->setState($newState);
        // todo this should be captured in a business rule
        $story->setOrdering($this->storyRepository->getMaximumOrdering($newState->getId()) + 10);

        $this->storyRepository->update($story);
    }
}
