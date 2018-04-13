<?php

namespace Area51\Factory;

use Area51\Entity\Room;

class RoomFactory
{
    const HALF_WIDTH_MAX  = 12;
    const HALF_WIDTH_MIN  = 16;
    const HALF_HEIGHT_MIN = 6;
    const HALF_HEIGHT_MAX = 10;

    /**
     * @return Room
     */
    public function make()
    {
        return new Room($this->calcWidth(), $this->calcHeight());
    }

    /**
     * @return int
     */
    protected function calcWidth()
    {
        return 1 + rand(self::HALF_WIDTH_MIN,  self::HALF_WIDTH_MAX) * 2;
    }

    /**
     * @return int
     */
    protected function calcHeight()
    {
        return 1 + rand(self::HALF_HEIGHT_MIN, self::HALF_HEIGHT_MAX) * 2;
    }
}