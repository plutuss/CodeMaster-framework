<?php

namespace Plutuss\SauceCore\Http\Middleware;

interface MiddlewareInterface
{

    public function check($middleware = []): void;
}