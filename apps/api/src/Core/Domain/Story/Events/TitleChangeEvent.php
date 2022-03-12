<?php

declare(strict_types=1);

namespace Core\Domain\Story\Events;

use Core\Domain\Story\Story;
use Core\Domain\Story\StoryTitle;

/** @psalm-immutable */
final class TitleChangeEvent extends StoryEvent
{
    public function __construct(
        Story $story,
        public readonly StoryTitle $oldTitle,
        public readonly StoryTitle $newTitle,
    ) {
        parent::__construct($story);
    }
}
