<?php

declare(strict_types=1);

namespace Core\Ports\Rest\State;

use Core\Application\Query\State\GetStateQuery;
use Core\Application\Query\State\GetStateQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;
use Slim\Exception\HttpNotFoundException;
use Webmozart\Assert\Assert;

use function sprintf;

class GetStateAction implements RestAction
{
    public function __construct(
        private readonly GetStateQueryHandler $getStateQueryHandler,
        private readonly JsonSerializer $jsonSerializer,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @throws HttpNotFoundException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        Assert::string($args['id']);
        $id    = $args['id'];
        $query = GetStateQuery::fromString($args['id']);
        $state = ($this->getStateQueryHandler)($query);

        if (! isset($state)) {
            throw new HttpNotFoundException(
                $request,
                sprintf('State with uuid %s not found', $id)
            );
        }

        return $this->jsonSerializer->setJsonBody($state, $response);
    }
}
