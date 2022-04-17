<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Command\Story\DeleteStoryCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\RestAction;

class DeleteStoryAction implements RestAction
{
    public function __construct(
        private readonly DeleteStoryCommandHandler $deleteStoryCommandHandler,
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
        $dataBag = DataBag::fromArray($args);
        $id      = $dataBag->getUuid('id');
        ($this->deleteStoryCommandHandler)($id);

        return $response->withStatus(204);
    }
}
