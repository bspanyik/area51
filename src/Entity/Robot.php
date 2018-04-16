<?php

namespace Area51\Entity;

class Robot
{
    const MAX_MOVE_DISTANCE = 5;

    /** @var string */
    private $id;

    /** @var string */
    private $operator = '';

    /** @var int */
    private $salary = 0;

    /** @var bool */
    private $escaped = false;

    /** @var Room */
    private $room;

    /**
     * Robot constructor.
     * @param string $id
     * @param string $operator
     * @param Room $room
     */
    public function __construct(string $id, string $operator, Room $room)
    {
        $this->id = $id;
        $this->operator = $operator;
        $this->room = $room;
    }

}
