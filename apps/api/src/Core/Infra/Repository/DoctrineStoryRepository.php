<?php

declare(strict_types=1);

namespace Core\Infra\Repository;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class DoctrineStoryRepository implements StoryRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function create(Story $story): void
    {
        $this->em->persist($story);
        $this->em->flush();
    }

    public function find(UuidInterface $id): Story|null
    {
        return $this->em->find(Story::class, $id);
    }
}
