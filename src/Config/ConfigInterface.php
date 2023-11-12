<?php

namespace Plutuss\SauceCore\Config;

interface ConfigInterface
{
    public function get(string $key, $default = null): mixed;
}
