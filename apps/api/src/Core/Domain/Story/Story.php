<?php

declare(strict_types=1);

namespace Core\Domain\Story;

use Core\Domain\Story\Events\StoryCreatedEvent;
use Core\Domain\Story\Events\TitleChangeEvent;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
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

    private function __construct(
        StoryTitle $title
    ) {
        $this->title     = $title;
        $this->id        = Uuid::uuid1();
        $this->createdAt = new DateTimeImmutable();
        $this->raise(new StoryCreatedEvent($this));
    }

    public static function create(StoryTitle $title): self
    {
        return new self($title);
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
}
