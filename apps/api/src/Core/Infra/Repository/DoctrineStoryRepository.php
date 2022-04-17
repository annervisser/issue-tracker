<?php

declare(strict_types=1);

namespace Core\Infra\Repository;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Assert;

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

    public function delete(UuidInterface $id): void
    {
        $story = $this->em->find(Story::class, $id);
        Assert::notNull($story);
        $this->em->remove($story);
        $this->em->flush();
    }

    /** {@inheritDoc} */
    public function list(): array
    {
        return $this->getRepository()->findAll();
    }

    /** {@inheritDoc} */
    public function inState(UuidInterface $stateId): array
    {
        return $this->getRepository()->findBy(['state' => $stateId]);
    }

    /** @return EntityRepository<Story> */
    private function getRepository(): EntityRepository
    {
        return $this->em->getRepository(Story::class);
    }
}
