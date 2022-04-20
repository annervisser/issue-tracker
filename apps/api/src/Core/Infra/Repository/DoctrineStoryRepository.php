<?php

declare(strict_types=1);

namespace Core\Infra\Repository;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
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

    public function update(Story $story): void
    {
        if (! $this->em->contains($story)) {
            throw new LogicException('Cannot store object not managed by entity manager');
        }

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
        return $this->getRepository()->findBy([], ['ordering' => 'ASC']);
    }

    /** {@inheritDoc} */
    public function inState(UuidInterface $stateId): array
    {
        return $this->getRepository()->findBy(['state' => $stateId], ['ordering' => 'ASC']);
    }

    public function getMaximumOrdering(UuidInterface $stateId): int
    {
        $result = $this->em->createQueryBuilder()
            ->select('COALESCE(MAX(s.ordering), 0)')
            ->from(Story::class, 's')
            ->where('s.state = :state')
            ->setParameter('state', $stateId, UuidBinaryOrderedTimeType::NAME)
            ->getQuery()
            ->getSingleScalarResult();
        Assert::integer($result);

        return $result;
    }

    /** @return EntityRepository<Story> */
    private function getRepository(): EntityRepository
    {
        return $this->em->getRepository(Story::class);
    }
}
