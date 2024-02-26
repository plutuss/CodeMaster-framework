<?php

declare(strict_types=1);

namespace Plutuss\CodeMaster;

use Plutuss\CodeMaster\Container\Container;

class App
{

    private readonly Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->container
            ->router
            ->dispatch();

    }

}