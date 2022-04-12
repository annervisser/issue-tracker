<?php

declare(strict_types=1);

namespace Shared\Ports\Data;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Assert;

class DataBag
{
    /** @param array<string, mixed> $data */
    private function __construct(
        private readonly array $data
    ) {
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): self
    {
        Assert::isMap($data);

        return new self($data);
    }

    public function getString(string $key): string
    {
        $value = $this->getValue($key);
        Assert::string($value);

        return $value;
    }

    public function getInteger(string $key): int
    {
        $value = $this->getValue($key);
        Assert::integer($value);

        return $value;
    }

    public function getBoolean(string $key): bool
    {
        $value = $this->getValue($key);
        Assert::boolean($value);

        return $value;
    }

    public function getUuid(string $key): UuidInterface
    {
        $value = $this->getValue($key);
        Assert::string($value);
        Assert::uuid($value);
        $uuid = Uuid::fromString($value);
        Assert::uuidV1($uuid);

        return $uuid;
    }

    private function getValue(string $key): mixed
    {
        Assert::keyExists($this->data, $key);

        return $this->data[$key];
    }
}
