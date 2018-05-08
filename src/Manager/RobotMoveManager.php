<?php

namespace Area51\Manager;

use Area51\Entity\Robot;

class RobotMoveManager
{
    /** @var RobotStorageManager */
    private $robotStorageManager;

    public function __construct(RobotStorageManager $robotStorageManager)
    {
        $this->robotStorageManager = $robotStorageManager;
    }

    /**
     * @param string $robotId
     * @param string $direction
     * @param int $distance
     * @return int
     */
    public function move(string $robotId, string $direction, int $distance): int
    {
        $robot = $this->getRobot($robotId);
        $realDistance = $robot->move($direction, $distance);
        $this->saveRobot($robot);

        return $realDistance;
    }

    /**
     * @param string $robotId
     * @return Robot
     */
    private function getRobot(string $robotId): Robot
    {
        return $this->robotStorageManager->restore($robotId);
    }

    /**
     * @param Robot $robot
     */
    private function saveRobot(Robot $robot)
    {
        $this->robotStorageManager->persist($robot);
    }

}
