<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Http\Middleware;

use Plutuss\SauceCore\Auth\AuthInterface;
use Plutuss\SauceCore\Http\Redirect\RedirectInterface;
use Plutuss\SauceCore\Http\Request\RequestInterface;

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