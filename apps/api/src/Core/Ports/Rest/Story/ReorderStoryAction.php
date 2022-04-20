<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Command\Story\ReorderStoryCommand;
use Core\Application\Command\Story\ReorderStoryCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\RestAction;

class ReorderStoryAction implements RestAction
{
    public function __construct(
        private readonly ReorderStoryCommandHandler $reorderStoryCommandHandler,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $storyId      = DataBag::fromArray($args)->getUuid('id');
        $afterStoryId = DataBag::fromRequestBody($request)->getUuidOrNull('afterStory');
        ($this->reorderStoryCommandHandler)(new ReorderStoryCommand($storyId, $afterStoryId));

        return $response->withStatus(204);
    }
}
