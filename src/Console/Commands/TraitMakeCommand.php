<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Console\Commands;


trait TraitMakeCommand
{

    protected string $path;

    protected string $content;
    protected string $filename;


    public function setFilename(string $filename): static
    {
        $this->filename = $filename;
        return $this;
    }


    public function createFile()
    {
        $file = fopen($this->path, "wb");
        fwrite($file, $this->content);
        fclose($file);
    }

    public function getPath(string $namespace): string
    {
        $this->path = app_path_from_console() . "{$namespace}{$this->filename}.php";
        return $this->path;
    }

}