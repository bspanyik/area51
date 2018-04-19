<?php

use Area51\System\Routing\Route;

return [
    '/robot' => [

        new Route(
            'GET',
            '#^/robot$#s',
            [Area51\Controller\RobotController::class, 'createAction'],
            []
        ),

        new Route(
            'GET',
            '#^/robot/(?P<id>\\d+)/move$#s',
            [\Area51\Controller\RobotController::class, 'moveAction'],
            []
        ),

        new Route(
            'GET',
            '#^/robot/(?P<id>\\d+)/escape$#s',
            [\Area51\Controller\RobotController::class, 'escapeAction']
        )
    ]
];
