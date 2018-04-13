<?php

namespace Area51\Entity;

class Robot
{
    const MAX_MOVE_DISTANCE = 5;

    /** @var string */
    protected $id;

    /** @var string */
    protected $operator = '';

    /** @var int */
    protected $salary = 0;

    /** @var bool */
    protected $escaped = false;

    /** @var Room */
    protected $room;

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
