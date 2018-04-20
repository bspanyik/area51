<?php

use Area51\Controller\RobotController;
use Area51\Manager\RobotCreationManager;
use Area51\System\Routing\Route;
use Area51\Validator\EmailValidator;

return [
    '/robot' => [

        new Route(
            'POST',
            '/robot',
            [RobotController::class, 'createAction'],
            [EmailValidator::class, 'email', RobotCreationManager::class]
        ),

        new Route(
            'GET',
            '/robot/(?P<id>[^/]++)/move',
            [RobotController::class, 'moveAction'],
            []
        ),

        new Route(
            'GET',
            '/robot/(?P<id>[^/]++)/escape',
            [RobotController::class, 'escapeAction']
        )
    ]
];
