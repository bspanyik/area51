<?php

use Area51\System\Routing\Route;
use Area51\Controller\RobotController;

return [
    '/robot' => [

        new Route(
            'GET',
            '/robot',
            [RobotController::class, 'createAction'],
            []
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
