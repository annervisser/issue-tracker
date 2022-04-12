<?php

declare(strict_types=1);

namespace Ports\Data;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shared\Ports\Data\DataBag;
use Throwable;

/** @covers \Shared\Ports\Data\DataBag */
class DataBagTest extends TestCase
{
    private const TEST_DATA = [
        'string' => 'abc',
        'string-false' => 'false',
        'string-number' => '16',
        'number' => 5,
        'negative-number' => -55,
        'true' => true,
        'false' => false,
        'uuid1' => 'e9b8a8a4-b8af-11ec-b27e-0242ac130003',
        'uuid4' => '8337591c-693f-4d92-a805-22d47d7ef03c',
    ];

    public function testGetString(): void
    {
        $data = DataBag::fromArray(self::TEST_DATA);
        self::assertEquals('abc', $data->getString('string'));
        $this->assertException(
            static fn () => $data->getString('number'),
            InvalidArgumentException::class,
            'Expected a string. Got: integer'
        );
        $this->assertException(
            static fn () => $data->getString('thiskeydoenstexist'),
            InvalidArgumentException::class,
            'Expected the key "thiskeydoenstexist" to exist.'
        );
    }

    public function testGetBoolean(): void
    {
        $data = DataBag::fromArray(self::TEST_DATA);
        self::assertEquals(true, $data->getBoolean('true'));
        self::assertEquals(false, $data->getBoolean('false'));
        $this->assertException(
            static fn () => $data->getBoolean('number'),
            InvalidArgumentException::class,
            'Expected a boolean. Got: integer'
        );
        $this->assertException(
            static fn () => $data->getBoolean('string-false'),
            InvalidArgumentException::class,
            'Expected a boolean. Got: string'
        );
        $this->assertException(
            static fn () => $data->getBoolean('thiskeydoenstexist'),
            InvalidArgumentException::class,
            'Expected the key "thiskeydoenstexist" to exist.'
        );
    }

    public function testGetInteger(): void
    {
        $data = DataBag::fromArray(self::TEST_DATA);
        self::assertEquals(5, $data->getInteger('number'));
        self::assertEquals(-55, $data->getInteger('negative-number'));
        $this->assertException(
            static fn () => $data->getInteger('string'),
            InvalidArgumentException::class,
            'Expected an integer. Got: string'
        );
        $this->assertException(
            static fn () => $data->getInteger('string-number'),
            InvalidArgumentException::class,
            'Expected an integer. Got: string'
        );
        $this->assertException(
            static fn () => $data->getInteger('thiskeydoenstexist'),
            InvalidArgumentException::class,
            'Expected the key "thiskeydoenstexist" to exist.'
        );
    }

    public function testGetUuid(): void
    {
        $data         = DataBag::fromArray(self::TEST_DATA);
        $expectedUuid = Uuid::fromString(self::TEST_DATA['uuid1']);
        $actualUuid   = $data->getUuid('uuid1');
        self::assertTrue($expectedUuid->equals($actualUuid), 'expected uuids to be equals');

        $this->assertException(
            static fn () => $data->getUuid('string'),
            InvalidArgumentException::class,
            'Value "abc" is not a valid UUID.'
        );
        $this->assertException(
            static fn () => $data->getUuid('uuid4'),
            InvalidArgumentException::class,
            'Expected uuid to be of type 1, Got: 4'
        );
        $this->assertException(
            static fn () => $data->getUuid('thiskeydoenstexist'),
            InvalidArgumentException::class,
            'Expected the key "thiskeydoenstexist" to exist.'
        );
    }

    /**
     * Asserts that the given callback throws the given exception.
     *
     * @param class-string<Throwable> $expectClass
     */
    protected function assertException(callable $callback, string $expectClass, string|null $expectMessage = null): void
    {
        try {
            $callback();
        } catch (Throwable $exception) {
            $this->assertInstanceOf($expectClass, $exception, 'An invalid exception was thrown');

            if (isset($expectMessage)) {
                $this->assertSame($expectMessage, $exception->getMessage(), 'Exception message doenst match expected');
            }

            return;
        }

        $this->fail('No exception was thrown');
    }
}
