<?php

declare(strict_types=1);

namespace Core\Infra\Repository;

use Core\Domain\State\State;
use Core\Domain\State\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class DoctrineStateRepository implements StateRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function create(State $state): void
    {
        $this->em->persist($state);
        $this->em->flush();
    }

    public function find(UuidInterface $id): State|null
    {
        return $this->em->find(State::class, $id);
    }

    /** {@inheritDoc} */
    public function list(): array
    {
        return $this->em->getRepository(State::class)->findAll();
    }
}
