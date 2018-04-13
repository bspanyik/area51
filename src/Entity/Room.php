<?php

namespace Area51\Entity;

class Room
{
    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /** @var int */
    protected $robotX;

    /** @var int */
    protected $robotY;

    /**
     * Room constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->initRobot();
    }

    protected function initRobot()
    {
        $this->robotX = rand(0, $this->width - 1);
        $this->robotY = rand(0, $this->height - 1);
    }

}