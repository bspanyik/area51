<?php
use Area51\Factory\RobotFactory;
use Area51\Factory\RoomFactory;
use Area51\System\Container;
use Area51\Validator\CreateValidator;
use Area51\Validator\MoveValidator;
use Area51\Manager\RobotStorageManager;
use Area51\Manager\RobotCreationManager;
use Area51\Manager\RobotMoveManager;
use Area51\Validator\RobotValidator;

return [
    RobotStorageManager::class => function() {
        return new RobotStorageManager(__DIR__ . '/../storage');
    },

    RobotValidator::class => function(Container $c) {
        return new RobotValidator($c->get(RobotStorageManager::class));
    },

    CreateValidator::class => function() {
        return new CreateValidator();
    },

    MoveValidator::class => function(Container $c) {
        return new MoveValidator($c->get(RobotValidator::class));
    },

    RoomFactory::class => function() {
        return new RoomFactory();
    },

    RobotFactory::class => function() {
        return new RobotFactory();
    },

    RobotCreationManager::class => function(Container $c) {
        return new RobotCreationManager($c->get(RobotStorageManager::class), $c->get(RobotFactory::class), $c->get(RoomFactory::class));
    },

    RobotMoveManager::class => function(Container $c) {
        return new RobotMoveManager($c->get(RobotStorageManager::class));
    },

];