<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Query\Story\ListStoryQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;

class ListStoriesAction implements RestAction
{
    public function __construct(
        private readonly ListStoryQueryHandler $listStoryQueryHandler,
        private readonly JsonSerializer $jsonSerializer,
    ) {
    }

    /** {@inheritDoc} */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $stories = ($this->listStoryQueryHandler)();

        return $this->jsonSerializer->setJsonBody($stories, $response);
    }
}
