<?php
use Area51\Factory\RobotFactory;
use Area51\Factory\RoomFactory;
use Area51\System\Container;
use Area51\Validator\EmailValidator;
use Area51\Manager\RobotStorageManager;
use Area51\Manager\RobotCreationManager;

return [
    EmailValidator::class => function() {
        return new EmailValidator();
    },

    RoomFactory::class => function() {
        return new RoomFactory();
    },

    RobotFactory::class => function() {
        return new RobotFactory();
    },

    RobotStorageManager::class => function() {
        return new RobotStorageManager(__DIR__ . '/../storage');
    },

    RobotCreationManager::class => function(Container $c) {
        return new RobotCreationManager($c->get(RobotStorageManager::class), $c->get(RobotFactory::class), $c->get(RoomFactory::class));
    },
];