<?php

namespace Plutuss\CodeMaster\Http\Redirect;

interface RedirectInterface
{
    public function to(string $url);
    public function back();
}