<?php

namespace Area51\Validator;

use Area51\Manager\RobotStorageManager;

class RobotValidator
{
    /** @var RobotStorageManager */
    private $robotStorageManager;

    /**
     * @param RobotStorageManager $robotStorageManager
     */
    public function __construct(RobotStorageManager $robotStorageManager)
    {
        $this->robotStorageManager = $robotStorageManager;
    }

    /**
     * @param string $robotId
     * @return array
     */
    public function validate(string $robotId): array
    {
        $violations = [];

        if (!$this->robotStorageManager->has($robotId)) {
            $violations[] = sprintf('Invalid robot id %s.', $robotId);
        }

        return $violations;
    }

}
