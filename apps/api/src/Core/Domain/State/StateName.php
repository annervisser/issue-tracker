<?php

declare(strict_types=1);

namespace Core\Domain\State;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

use function trim;

/** @psalm-immutable */
#[Embeddable]
class StateName
{
    /** @var non-empty-string */
    #[Column(length: 150)]
    protected readonly string $name;

    public function __construct(string $name)
    {
        $name = trim($name);
        Assert::stringNotEmpty($name);
        Assert::maxLength($name, 150);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
