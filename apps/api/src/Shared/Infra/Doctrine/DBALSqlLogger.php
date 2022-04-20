<?php

declare(strict_types=1);

namespace Shared\Infra\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;
use Psr\Log\LoggerInterface;

/** @psalm-suppress DeprecatedInterface */
class DBALSqlLogger implements SQLLogger
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /** {@inheritDoc} */
    public function startQuery($sql, ?array $params = null, ?array $types = null): void
    {
        $this->logger->debug($sql, [
            'params' => $params,
            'types' => $types,
        ]);
    }

    public function stopQuery(): void
    {
    }
}
