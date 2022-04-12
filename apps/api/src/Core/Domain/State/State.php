<?php

declare(strict_types=1);

namespace Core\Domain\State;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class State
{
    #[Id, Column(type: 'uuid_binary_ordered_time')]
    private readonly UuidInterface $id;

    #[Embedded(columnPrefix: false)]
    private StateName $name;

    private function __construct(
        StateName $name
    ) {
        $this->name = $name;
        $this->id   = Uuid::uuid1();
    }

    public static function create(StateName $name): self
    {
        return new self($name);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): StateName
    {
        return $this->name;
    }
}
