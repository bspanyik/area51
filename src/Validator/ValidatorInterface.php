<?php

namespace Area51\Validator;

interface ValidatorInterface
{
    public function validate($data): bool;
}