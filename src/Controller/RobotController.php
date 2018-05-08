<?php

namespace Area51\Controller;

use Area51\Manager\RobotCreationManager;
use Area51\Manager\RobotMoveManager;
use Area51\Request\CreateRequest;
use Area51\Request\MoveRequest;
use Area51\System\Http\Request;
use Area51\Validator\CreateValidator;
use Area51\Validator\MoveValidator;
use InvalidArgumentException;

class RobotController implements ControllerInterface
{
    /**
     * @param Request $request
     * @param CreateValidator $validator
     * @param RobotCreationManager $robotCreationManager
     */
    public function createAction(Request $request, CreateValidator $validator, RobotCreationManager $robotCreationManager)
    {
        $createRequest = new CreateRequest($request->getParam('email'));

        $violations = $validator->validate($createRequest);
        if (count($violations) > 0) {
            throw new InvalidArgumentException(sprintf('Invalid create request. [%s]', implode(' | ', $violations)));
        }

        $robotId = $robotCreationManager->create($createRequest->getEmail());

        echo sprintf('Robot (id: %s) successfully created.', $robotId);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param MoveValidator $validator
     * @param RobotMoveManager $robotMoveManager
     */
    public function moveAction(string $id, Request $request, MoveValidator $validator, RobotMoveManager $robotMoveManager)
    {
        $moveRequest = new MoveRequest($id, $request->getParam('direction'), $request->getParam('distance'));

        $violations = $validator->validate($moveRequest);
        if (count($violations) > 0) {
            throw new InvalidArgumentException(sprintf('Invalid move request. [%s]', implode(' | ', $violations)));
        }

        $distance = $robotMoveManager->move($moveRequest->getRobotId(), $moveRequest->getDirection(), $moveRequest->getDistance());

        echo sprintf('The robot moved %s cells', $distance);
    }

    /**
     *
     */
    public function escapeAction()
    {
        die('The Robot Escapes');
    }

}
