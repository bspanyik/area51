<?php

namespace Area51\Manager;

use Area51\Factory\RobotFactory;
use Area51\Factory\RoomFactory;

class RobotCreationManager
{
    /**
     * @var RobotStorageManager
     */
    private $storageManager;

    /**
     * @var RobotFactory
     */
    private $robotFactory;

    /**
     * @var RoomFactory
     */
    private $roomFactory;

    /**
     * @param RobotStorageManager $storageManager
     * @param RobotFactory $robotFactory
     * @param RoomFactory $roomFactory
     */
    public function __construct(RobotStorageManager $storageManager, RobotFactory $robotFactory, RoomFactory $roomFactory)
    {
        $this->storageManager = $storageManager;
        $this->robotFactory = $robotFactory;
        $this->roomFactory = $roomFactory;
    }

    /**
     * @param string $email
     * @return string
     */
    public function create(string $email): string
    {
        $room  = $this->roomFactory->make();
        $robot = $this->robotFactory->make($email, $room);
        $this->storageManager->persist($robot);

        return $robot->getId();
    }
}
