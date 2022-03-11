<?php

declare(strict_types=1);

namespace Shared\Infra\Settings;

use Webmozart\Assert\Assert;

use function array_replace_recursive;
use function explode;

/** @psalm-immutable */
final class Settings implements SettingsInterface
{
    /** @psalm-param array<string, mixed> $settings */
    public function __construct(private readonly array $settings = [])
    {
    }

    public function get(string $key): mixed
    {
        $parts  = explode('.', $key);
        $return = $this->settings;
        foreach ($parts as $part) {
            Assert::isArray($return);
            Assert::keyExists($return, $part);
            /** @psalm-suppress MixedAssignment */
            $return = $return[$part];
        }

        return $return;
    }

    /** @psalm-param array<string, mixed> $settings */
    public function addSettings(array $settings): self
    {
        $settings = array_replace_recursive($this->settings, $settings);
        Assert::isMap($settings);

        return new self($settings);
    }
}
