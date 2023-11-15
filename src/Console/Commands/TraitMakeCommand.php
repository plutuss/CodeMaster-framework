<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Console\Commands;


trait TraitMakeCommand
{

    protected string $path;

    protected string $content;
    protected string $filename;


    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename): static
    {
        $this->filename = $filename;
        return $this;
    }


    /**
     * @return void
     */
    public function createFile(): void
    {
        $file = fopen($this->path, "wb");
        fwrite($file, $this->content);
        fclose($file);
    }

    /**
     * @param string $namespace
     * @return string
     */
    public function getPath(string $namespace): string
    {
        $this->path = app_path_from_console() . "{$namespace}{$this->filename}.php";
        return $this->path;
    }

}