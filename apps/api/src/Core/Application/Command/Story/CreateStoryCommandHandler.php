<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Core\Domain\State\StateRepository;
use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Core\Domain\Story\StoryTitle;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

final class CreateStoryCommandHandler
{
    public function __construct(
        private readonly StoryRepository $storyRepository,
        private readonly StateRepository $stateRepository,
    ) {
    }

    public function __invoke(CreateStoryCommand $command): UuidInterface
    {
        $state = $this->stateRepository->find($command->stateId);
        Assert::notNull($state, 'Unknown state provided');
        $ordering = $this->storyRepository->getMaximumOrdering($command->stateId) + 10;
        $story    = Story::create(new StoryTitle($command->title), $state, $ordering);
        $this->storyRepository->create($story);

        return $story->getId();
    }
}
