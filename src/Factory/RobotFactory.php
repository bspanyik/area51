<?php

namespace Area51\Factory;

use Area51\Entity\Robot;

class RobotFactory
{
    /** @var RoomFactory */
    protected $roomFactory;

    /**
     * RobotFactory constructor.
     * @param RoomFactory $roomFactory
     */
    public function __construct(RoomFactory $roomFactory)
    {
        $this->roomFactory = $roomFactory;
    }

    /**
     * @param string $operator
     * @return Robot
     */
    public function make(string $operator)
    {
        return new Robot($this->generateUniqueId(), $operator, $this->roomFactory->make());
    }

    /**
     * @return string
     */
    protected function generateUniqueId()
    {
        return uniqid();
    }
}