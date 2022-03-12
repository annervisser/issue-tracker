<?php

declare(strict_types=1);

namespace Shared\Domain;

use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert as WebMozartAssert;

class Assert extends WebMozartAssert
{
    public static function uuidV1(UuidInterface $value, string $message = ''): void
    {
        /** @psalm-suppress DeprecatedMethod getVersion is deprecated but also used in doctrine-uuid */
        self::eq($value->getVersion(), 1, $message);
    }
}
