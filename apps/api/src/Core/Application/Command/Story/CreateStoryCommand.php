<?php

namespace Core\Application\Command\Story;

final class CreateStoryCommand
{
    public function __construct(
        public readonly string $title,
    ) {
    }
}
