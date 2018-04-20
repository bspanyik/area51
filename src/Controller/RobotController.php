<?php

namespace Area51\Controller;

use Area51\Manager\RobotCreationManager;
use Area51\Validator\EmailValidator;
use InvalidArgumentException;

class RobotController implements ControllerInterface
{
    public function createAction(EmailValidator $validator, $email, RobotCreationManager $robotCreationManager)
    {
        if (false === $validator->validate($email)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        $robotId = $robotCreationManager->create($email);

        echo sprintf('Robot (id: %s) successfully created.', $robotId);
    }

    public function moveAction()
    {
        die('The Robot Moves!');
    }

    public function escapeAction()
    {
        die('The Robot Escapes');
    }
}
