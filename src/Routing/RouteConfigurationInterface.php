<?php

namespace  Plutuss\CodeMaster\Routing;

interface RouteConfigurationInterface
{
    public function hasMiddleware(): bool;

    public function name($name): static;

    public function middleware(string|array $middleware): static;

    public function prefix(string $prefix): static;
}