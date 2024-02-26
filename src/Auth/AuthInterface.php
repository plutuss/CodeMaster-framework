<?php

namespace Plutuss\CodeMaster\Auth;


use Plutuss\CodeMaster\Model\Model;

interface AuthInterface
{
    public function attempt(string $username, string $password): bool;

    public function logout(): void;

    public function check(): bool;

    public function user(): Model;

    public function id(): ?int;


}