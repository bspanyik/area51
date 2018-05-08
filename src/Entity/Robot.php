<?php

namespace Area51\Entity;

class Robot
{
    const MAX_MOVE_DISTANCE = 5;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $operator = '';

    /**
     * @var int
     */
    private $cycle = 0;

    /**
     * @var int
     */
    private $salary = 0;

    /**
     * @var bool
     */
    private $escaped = false;

    /**
     * @var Room
     */
    private $room;

    /**
     * @param string $id
     * @param string $operator
     * @param Room $room
     */
    public function __construct(string $id, string $operator, Room $room)
    {
        $this->id = $id;
        $this->operator = $operator;
        $this->cycle = 0;
        $this->room = $room;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * increases roboty lifecycle when robot wakes up
     */
    public function __wakeup()
    {
        $this->cycle += 1;
    }

    /**
     * asks Room to control Robot movement
     *
     * @param string $direction
     * @param int $distance
     * @return int
     */
    public function move(string $direction, int $distance): int
    {
        $distance = min($distance, self::MAX_MOVE_DISTANCE);

        return $this->room->handleRobotMove($direction, $distance);
    }

}
