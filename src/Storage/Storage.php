<?php

declare(strict_types=1);


namespace  Plutuss\CodeMaster\Storage;

use Plutuss\CodeMaster\Config\ConfigInterface;

readonly class Storage implements StorageInterface
{
    public function __construct(
        private ConfigInterface $config,
    )
    {
    }

    /**
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
//        $url = $this->config->get('app.url', 'http://localhost:8000');
        $url = $this->config->get('filestorage.url', 'http://localhost:8000');

        return "$url/$path";
    }

    /**
     * @param string $path
     * @return string
     */
    public function get(string $path): string
    {
        return file_get_contents($this->storagePath($path));
    }

    /**
     * @param string $path
     * @return string
     */
    private function storagePath(string $path): string
    {
        $pathStorage = $this->config->get('filestorage.path', '/storage/');
        return $pathStorage . $path;
    }
}