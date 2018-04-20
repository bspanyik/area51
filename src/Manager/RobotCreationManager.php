<?php

namespace Area51\Manager;

use Area51\Factory\RobotFactory;

class RobotCreationManager
{
    /**
     * @var RobotStorageManager
     */
    private $storageManager;

    /**
     * @var RobotFactory
     */
    private $factory;

    /**
     * @param RobotStorageManager $storageManager
     * @param RobotFactory $factory
     */
    public function __construct(RobotStorageManager $storageManager, RobotFactory $factory)
    {
        $this->storageManager = $storageManager;
        $this->factory = $factory;
    }

    /**
     * @param string $email
     * @return string
     */
    public function create(string $email): string
    {
        $robot = $this->factory->make($email);
        $this->storageManager->persist($robot);
        return $robot->getId();
    }
}
