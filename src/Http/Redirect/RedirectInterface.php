<?php

namespace Plutuss\SauceCore\Http\Redirect;

interface RedirectInterface
{
    public function to(string $url);
    public function back();
}