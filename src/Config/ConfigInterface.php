<?php

namespace Plutuss\CodeMaster\Config;

interface ConfigInterface
{
    public function get(string $key, $default = null): mixed;
}
