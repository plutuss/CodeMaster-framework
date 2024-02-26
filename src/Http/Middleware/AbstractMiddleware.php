<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\Http\Middleware;

use Plutuss\CodeMaster\Auth\AuthInterface;
use Plutuss\CodeMaster\Http\Redirect\RedirectInterface;
use Plutuss\CodeMaster\Http\Request\RequestInterface;

abstract class AbstractMiddleware
{

    public function __construct(
        protected RequestInterface  $request,
        protected RedirectInterface $redirect,
        protected AuthInterface     $auth
    )
    {
    }

    abstract public function handle(): void;

}