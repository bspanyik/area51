<?php

namespace Area51\Entity;

class Room
{
    const DIRECTION_UP    = 'up';
    const DIRECTION_DOWN  = 'down';
    const DIRECTION_LEFT  = 'left';
    const DIRECTION_RIGHT = 'right';

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $robotX;

    /**
     * @var int
     */
    private $robotY;

    /**
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->initRobot();
    }

    /**
     * init Robot position in Room
     */
    private function initRobot()
    {
        $this->robotX = rand(0, $this->width - 1);
        $this->robotY = rand(0, $this->height - 1);
    }

    /**
     * @return array
     */
    public static function getValidDirections()
    {
        return [
            self::DIRECTION_UP,
            self::DIRECTION_DOWN,
            self::DIRECTION_LEFT,
            self::DIRECTION_RIGHT,
        ];
    }

    /**
     * handle Robot movement
     *
     * @param $direction
     * @param $distance
     * @return int
     */
    public function handleRobotMove($direction, $distance): int
    {
        if (self::DIRECTION_LEFT == $direction) {
            return $this->robotMovesLeft($distance);
        }

        if (self::DIRECTION_UP === $direction) {
            return $this->robotMovesUp($distance);
        }

        if (self::DIRECTION_RIGHT == $direction) {
            return $this->robotMovesRight($distance);
        }

        if (self::DIRECTION_DOWN === $direction) {
            return $this->robotMovesDown($distance);
        }

        return 0;
    }

    /**
     * @param int $distance
     * @return int
     */
    private function robotMovesLeft($distance): int
    {
        $distance = min($this->robotX, $distance);
        $this->robotX -= $distance;

        return $distance;
    }

    /**
     * @param int $distance
     * @return int
     */
    private function robotMovesRight($distance): int
    {
        $wallDistance = ($this->width - 1) - $this->robotX;
        $distance = min($wallDistance, $distance);
        $this->robotX += $distance;

        return $distance;
    }

    /**
     * @param int $distance
     * @return int
     */
    private function robotMovesDown($distance): int
    {
        $distance = min($this->robotY, $distance);
        $this->robotY -= $distance;

        return $distance;
    }

    /**
     * @param int $distance
     * @return int
     */
    private function robotMovesUp($distance): int
    {
        $wallDistance = ($this->height - 1) - $this->robotY;
        $distance = min($wallDistance, $distance);
        $this->robotY += $distance;

        return $distance;
    }
}