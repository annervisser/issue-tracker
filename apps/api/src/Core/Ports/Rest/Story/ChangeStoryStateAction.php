<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Command\Story\ChangeStoryStateCommand;
use Core\Application\Command\Story\ChangeStoryStateCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\RestAction;

class ChangeStoryStateAction implements RestAction
{
    public function __construct(
        private readonly ChangeStoryStateCommandHandler $changeStoryStateCommandHandler,
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
        $storyId    = DataBag::fromArray($args)->getUuid('id');
        $newStateId = DataBag::fromRequestBody($request)->getUuid('newState');
        ($this->changeStoryStateCommandHandler)(new ChangeStoryStateCommand($storyId, $newStateId));

        return $response->withStatus(204);
    }
}
