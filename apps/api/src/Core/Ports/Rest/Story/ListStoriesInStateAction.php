<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Query\Story\ListStoriesInStateQuery;
use Core\Application\Query\Story\ListStoriesInStateQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;

class ListStoriesInStateAction implements RestAction
{
    public function __construct(
        private readonly ListStoriesInStateQueryHandler $listStoryQueryHandler,
        private readonly JsonSerializer $jsonSerializer,
    ) {
    }

    /** {@inheritDoc} */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $dataBag = DataBag::fromArray($args);
        $stories = ($this->listStoryQueryHandler)(new ListStoriesInStateQuery($dataBag->getUuid('stateId')));

        return $this->jsonSerializer->setJsonBody($stories, $response);
    }
}
