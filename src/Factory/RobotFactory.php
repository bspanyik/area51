<?php

namespace Area51\Factory;

use Area51\Entity\Robot;
use Area51\Entity\Room;

class RobotFactory
{
    /**
     * @param string $operator
     * @param Room $room
     * @return Robot
     */
    public function make(string $operator, Room $room)
    {
        return new Robot($this->generateUniqueId(), $operator, $room);
    }

    /**
     * @return string
     */
    private function generateUniqueId()
    {
        return uniqid();
    }
}
