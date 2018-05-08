<?php

namespace Area51\Request;

class MoveRequest
{
    /** @var string */
    private $robotId;

    /** @var string */
    private $direction;

    /** @var int */
    private $distance;

    public function __construct(string $robotId, string $direction, int $distance)
    {
        $this->robotId   = $robotId;
        $this->direction = $direction;
        $this->distance  = $distance;
    }

    /**
     * @return string
     */
    public function getRobotId(): string
    {
        return $this->robotId;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

}
