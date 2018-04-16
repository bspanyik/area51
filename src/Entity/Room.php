<?php

namespace Area51\Entity;

class Room
{
    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var int */
    private $robotX;

    /** @var int */
    private $robotY;

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

    private function initRobot()
    {
        $this->robotX = rand(0, $this->width - 1);
        $this->robotY = rand(0, $this->height - 1);
    }

}