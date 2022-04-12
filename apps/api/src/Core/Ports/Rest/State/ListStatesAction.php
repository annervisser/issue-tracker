<?php

declare(strict_types=1);

namespace Core\Ports\Rest\State;

use Core\Application\Query\State\ListStatesQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;

class ListStatesAction implements RestAction
{
    public function __construct(
        private readonly ListStatesQueryHandler $listStateQueryHandler,
        private readonly JsonSerializer $jsonSerializer,
    ) {
    }

    /** {@inheritDoc} */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $stories = ($this->listStateQueryHandler)();

        return $this->jsonSerializer->setJsonBody($stories, $response);
    }
}
