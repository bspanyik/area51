<?php

namespace Area51\Validator;

class EmailValidator implements ValidatorInterface
{
    /**
     * @param string $email
     * @return bool
     */
    public function validate($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
