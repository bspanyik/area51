<?php

namespace Area51\Validator;

use Area51\Entity\Room;
use Area51\Request\MoveRequest;

class MoveValidator implements ValidatorInterface
{
    /** @var RobotValidator */
    private $robotValidator;

    public function __construct(RobotValidator $robotValidator)
    {
        $this->robotValidator = $robotValidator;
    }

    /**
     * @param MoveRequest $request
     * @return array
     */
    public function validate($request): array
    {
        $violations = [];

        if (!$this->validateRequest($request)) {
            $violations[] = 'Invalid request.';
        }

        $robotViolations = $this->robotValidator->validate($request->getRobotId());
        if (count($robotViolations) > 0) {
            $violations = array_merge($violations, $robotViolations);
        }

        if (!$this->validateDirection($request->getDirection())) {
            $violations[] = sprintf('Invalid direction: %s.', $request->getDirection());
        }

        if (!$this->validateDistance($request->getDistance())) {
            $violations[] = sprintf('Invalid distance: %s.', $request->getDistance());
        }

        return $violations;
    }

    private function validateRequest($request)
    {
        return $request instanceof MoveRequest;
    }

    private function validateDirection($direction)
    {
        return in_array($direction, Room::getValidDirections());
    }

    private function validateDistance($distance)
    {
        return (is_int($distance) && $distance > 0);
    }
}