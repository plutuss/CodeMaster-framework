<?php

namespace Plutuss\SauceCore\Validator;

interface ValidatorInterface
{
    public function validate(array $data, array $rules): bool;

    public function errors(): array;
    public function validatedData(): array;
}
