<?php

declare(strict_types=1);

namespace Core\Domain\Story;

use Core\Domain\State\State;
use Core\Domain\Story\Events\StoryCreatedEvent;
use Core\Domain\Story\Events\TitleChangeEvent;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Aggregate;

#[Entity]
class Story extends Aggregate
{
    #[Id, Column(type: 'uuid_binary_ordered_time')]
    private readonly UuidInterface $id;

    #[Embedded(columnPrefix: false)]
    private StoryTitle $title;

    #[Column]
    private readonly DateTimeImmutable $createdAt;

    #[ManyToOne(fetch: 'EAGER'), JoinColumn(nullable: false)]
    private State $state;

    private function __construct(
        StoryTitle $title,
        State $state
    ) {
        $this->title = $title;
        $this->state = $state;

        $this->id        = Uuid::uuid1();
        $this->createdAt = new DateTimeImmutable();
        $this->raise(new StoryCreatedEvent($this));
    }

    public static function create(
        StoryTitle $title,
        State $state
    ): self {
        return new self($title, $state);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTitle(): StoryTitle
    {
        return $this->title;
    }

    public function changeTitle(StoryTitle $title): void
    {
        $this->raise(new TitleChangeEvent($this, $this->title, $title));
        $this->title = $title;
    }

    public function getState(): State
    {
        return $this->state;
    }
}
