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

    /** {@inheritDoc} */
    public function list(): array
    {
        return $this->em->getRepository(Story::class)->findAll();
    }

    /** {@inheritDoc} */
    public function inState(UuidInterface $stateId): array
    {
        return $this->em->getRepository(Story::class)->findBy(['state' => $stateId]);
    }
}
