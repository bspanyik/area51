<?php

use Area51\Controller\RobotController;
use Area51\Manager\RobotCreationManager;
use Area51\Manager\RobotMoveManager;
use Area51\System\Http\Request;
use Area51\System\Routing\Route;
use Area51\Validator\CreateValidator;
use Area51\Validator\MoveValidator;

return [
    '/robot' => [

        new Route(
            'POST',
            '/robot',
            [RobotController::class, 'createAction'],
            [Request::class, CreateValidator::class, RobotCreationManager::class]
        ),

        new Route(
            'PUT',
            '/robot/(?P<id>[^/]++)/move',
            [RobotController::class, 'moveAction'],
            ['id', Request::class, MoveValidator::class, RobotMoveManager::class]
        ),

        new Route(
            'GET',
            '/robot/(?P<id>[^/]++)/escape',
            [RobotController::class, 'escapeAction']
        )
    ]
];
