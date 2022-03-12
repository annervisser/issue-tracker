<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Query\Story\GetStoryQuery;
use Core\Application\Query\Story\GetStoryQueryHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;
use Slim\Exception\HttpNotFoundException;
use Webmozart\Assert\Assert;

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
    ): ResponseInterface {
        Assert::string($args['id']);
        $id    = $args['id'];
        $query = GetStoryQuery::fromString($args['id']);
        $story = ($this->getStoryQueryHandler)($query);

        if (! isset($story)) {
            throw new HttpNotFoundException(
                $request,
                sprintf('Story with uuid %s not found', $id)
            );
        }

        return $this->jsonSerializer->setJsonBody($story, $response);
    }
}
