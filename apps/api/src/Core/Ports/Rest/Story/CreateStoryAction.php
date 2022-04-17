<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Command\Story\CreateStoryCommand;
use Core\Application\Command\Story\CreateStoryCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Ports\Data\DataBag;
use Shared\Ports\Rest\JsonSerializer;
use Shared\Ports\Rest\RestAction;
use Webmozart\Assert\Assert;

class CreateStoryAction implements RestAction
{
    public function __construct(
        private readonly CreateStoryCommandHandler $createStoryCommandHandler,
        private readonly JsonSerializer $jsonSerializer
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
        $requestData = $request->getParsedBody();
        Assert::isArray($requestData);
        $dataBag = DataBag::fromArray($requestData);
        $command = new CreateStoryCommand(
            $dataBag->getString('title'),
            $dataBag->getUuid('stateId')
        );
        $storyId = ($this->createStoryCommandHandler)($command);

        return $this->jsonSerializer->setJsonBody(['storyId' => $storyId], $response);
    }
}
