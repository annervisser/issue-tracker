<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Core\Ports\Rest\State\GetStateAction;
use Core\Ports\Rest\State\ListStatesAction;
use Core\Ports\Rest\Story\CreateStoryAction;
use Core\Ports\Rest\Story\GetStoryAction;
use Core\Ports\Rest\Story\ListStoriesAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// phpcs:disable SlevomatCodingStandard.Functions.StaticClosure.ClosureNotStatic -- router requires non-static callbacks
return static function (App $app): void {
//    $app->options('/{routes:.*}', static function (Request $request, Response $response) {
//        // CORS Pre-Flight OPTIONS Request Handler
//        return $response;
//    });

    $app->group('/stories', function (RouteCollectorProxy $group): void {
        $group->get('', ListStoriesAction::class);
        $group->get('/{id}', GetStoryAction::class);
        $group->post('', CreateStoryAction::class);
    });

    $app->group('/states', function (RouteCollectorProxy $group): void {
        $group->get('', ListStatesAction::class);
        $group->get('/{id}', GetStateAction::class);
//        $group->post('', CreateStoryAction::class);
    });

//    $app->group('/users', static function (Group $group): void {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
};
