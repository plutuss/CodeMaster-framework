<?php

declare(strict_types=1);

namespace Plutuss\SauceCore\Console;

use Symfony\Component\Console\Application;

class Console
{

    private array $commands;

    /**
     * @return void
     */
    public function handler(): void
    {
        $application = new Application();

        if (!empty($this->commands)) {
            foreach ($this->commands as $command) {
                $application->add(new $command());
            }
        }

        $application->run();
    }

    /**
     * @param $commands
     * @return $this
     */
    public function setCommands($commands): static
    {
        $this->commands = $commands;
        return $this;
    }
}