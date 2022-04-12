<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Query\Story\GetStoryQuery;
use Core\Application\Query\Story\GetStoryQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;
use Slim\Exception\HttpNotFoundException;

use function sprintf;

class GetStoryAction implements RestAction
{
    public function __construct(
        private readonly GetStoryQueryHandler $getStoryQueryHandler,
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
    ): ResponseInterface
    {
        $dataBag = DataBag::fromArray($args);
        $id      = $dataBag->getUuid('id');
        $query   = new GetStoryQuery($id);
        $story   = ($this->getStoryQueryHandler)($query);

        if (! isset($story)) {
            throw new HttpNotFoundException(
                $request,
                sprintf('Story with uuid %s not found', $id->toString())
            );
        }

        return $this->jsonSerializer->setJsonBody($story, $response);
    }
}
