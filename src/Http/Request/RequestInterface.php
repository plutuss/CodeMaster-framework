<?php

namespace  Plutuss\SauceCore\Http\Request;

use Plutuss\SauceCore\Upload\UploadedFileInterface;
use Plutuss\SauceCore\Validator\Validator;

interface RequestInterface
{
    public static function createFromGlobals(): static;

    public function url(): false|string;

    public function method(): mixed;

    public function input(string $key, $default = null): mixed;

    public function setValidator(Validator $validator): void;

    public function validator(array $rules): bool;
    public function validatedData(): array;

    public function errors(): array;

    public function file(string $key): ?UploadedFileInterface;
}