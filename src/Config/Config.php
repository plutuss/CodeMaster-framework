<?php

namespace Plutuss\SauceCore\Config;

use const Plutuss\SauceCore\Config\APP_DIR;

class Config implements ConfigInterface
{
    public function get(string $key, $default = null): mixed
    {
        [$file, $key] = explode('.', $key);

        $configPath = $this->getPathConfig($file);

        if (!file_exists($configPath)) {
            return $default;
        }

        $config = require $configPath;

        return $config[$key] ?? $default;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getPathConfig(string $file): string
    {
        return APP_DIR . "/../config/{$file}.php";
    }
}
