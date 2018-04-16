<?php

namespace Area51\Factory;

use Area51\Entity\Room;

class RoomFactory
{
    const HALF_WIDTH_MIN  = 12;
    const HALF_WIDTH_MAX  = 16;
    const HALF_HEIGHT_MIN = 6;
    const HALF_HEIGHT_MAX = 10;

    /**
     * @return Room
     */
    public function make(): Room
    {
        return new Room($this->calcWidth(), $this->calcHeight());
    }

    /**
     * @return int
     */
    private function calcWidth(): int
    {
        return $this->createOddValue(rand(self::HALF_WIDTH_MIN,  self::HALF_WIDTH_MAX));
    }

    /**
     * @return int
     */
    private function calcHeight(): int
    {
        return $this->createOddValue(rand(self::HALF_HEIGHT_MIN, self::HALF_HEIGHT_MAX));
    }

    /**
     * @param int $value
     * @return int
     */
    private function createOddValue(int $value): int
    {
        return 1 + $value * 2;
    }
}
