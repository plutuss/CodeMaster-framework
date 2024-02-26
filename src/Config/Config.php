<?php

namespace Plutuss\CodeMaster\Config;

use const Plutuss\CodeMaster\Config\APP_DIR;

class Config implements ConfigInterface
{
    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
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
        return root_dir() . "/../config/{$file}.php";
    }
}
