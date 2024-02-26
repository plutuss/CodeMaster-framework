<?php

namespace  Plutuss\CodeMaster\Http\Request;

use Plutuss\CodeMaster\Upload\UploadedFileInterface;
use Plutuss\CodeMaster\Validator\Validator;

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