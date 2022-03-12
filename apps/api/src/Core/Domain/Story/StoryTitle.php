<?php

declare(strict_types=1);

namespace Core\Domain\Story;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

use function trim;

/** @psalm-immutable */
#[Embeddable]
class StoryTitle
{
    /** @var non-empty-string */
    #[Column(name: 'title', type: 'string', length: 150)]
    protected readonly string $title;

    public function __construct(string $title)
    {
        $title = trim($title);
        Assert::stringNotEmpty($title);
        Assert::maxLength($title, 150);
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
