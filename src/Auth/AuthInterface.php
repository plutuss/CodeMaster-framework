<?php

namespace Plutuss\SauceCore\Auth;



use Plutuss\SauceCore\Model\Model;

interface AuthInterface
{
    public function attempt(string $email, string $password): bool;

    public function logout(): void;

    public function check(): bool;

    public function user(): Model;

    public function id(): ?int;

    public function table(): string;

    public function username(): string;

    public function password(): string;

    public function sessionField(): string;
}