<?php

namespace Area51\Manager;

use Area51\Entity\Robot;
use InvalidArgumentException;

class RobotStorageManager
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $path = rtrim($path, '/');
        if (!is_dir($path)) {
            mkdir($path);
        }
        $this->path = $path . '/';
    }

    /**
     * @param Robot $robot
     * @return bool
     */
    public function persist(Robot $robot): bool
    {
        return false !== file_put_contents($this->path . $robot->getId(), serialize($robot));
    }

    /**
     * @param string $robotId
     * @return Robot
     * @throws InvalidArgumentException
     */
    public function restore(string $robotId): Robot
    {
        if (file_exists($this->path . $robotId)) {
            return unserialize(file_get_contents($this->path . $robotId));
        }

        throw new InvalidArgumentException(sprintf('Invalid robot id.'));
    }
}