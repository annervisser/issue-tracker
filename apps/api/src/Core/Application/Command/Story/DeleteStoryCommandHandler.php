<?php

declare(strict_types=1);

namespace Core\Application\Command\Story;

use Core\Domain\Story\StoryRepository;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidInterface;

final class DeleteStoryCommandHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly StoryRepository $storyRepository,
    ) {
    }

    public function __invoke(UuidInterface $id): void
    {
        $this->logger->info('Deleting story', ['id' => $id->toString()]);
        $this->storyRepository->delete($id);
    }
}
