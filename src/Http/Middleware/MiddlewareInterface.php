<?php

namespace Plutuss\CodeMaster\Http\Middleware;

interface MiddlewareInterface
{

    public function check($middleware = []): void;
}