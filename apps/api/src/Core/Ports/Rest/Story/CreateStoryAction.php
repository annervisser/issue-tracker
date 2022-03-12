<?php

declare(strict_types=1);

namespace Core\Ports\Rest\Story;

use Core\Application\Command\Story\CreateStoryCommand;
use Core\Application\Command\Story\CreateStoryCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
        $command = new CreateStoryCommand((string) $requestData['title']);
        $storyId = ($this->createStoryCommandHandler)($command);

        return $this->jsonSerializer->setJsonBody(['storyId' => $storyId], $response);
    }
}
