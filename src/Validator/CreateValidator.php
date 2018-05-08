<?php

namespace Area51\Validator;

use Area51\Request\CreateRequest;

class CreateValidator implements ValidatorInterface
{
    /**
     * @param CreateRequest $request
     * @return array
     */
    public function validate($request): array
    {
        $violations = [];

        if (!$this->validateRequest($request)) {
            $violations[] = 'Invalid request.';
        }

        if (!$this->validateEmail($request->getEmail())) {
            $violations[] = sprintf('Invalid email address: %s.', $request->getEmail());
        }

        return $violations;
    }

    /**
     * @param CreateRequest $request
     * @return bool
     */
    private function validateRequest($request): bool
    {
        return $request instanceof CreateRequest;
    }

    /**
     * @param string $email
     * @return bool
     */
    private function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

}
