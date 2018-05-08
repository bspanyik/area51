<?php

namespace Area51\Validator;

interface ValidatorInterface
{
    public function validate($request): array;
}
