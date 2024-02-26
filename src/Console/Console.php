<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\Console;

use Plutuss\CodeMaster\Console\Commands\DefaultCommand;
use Symfony\Component\Console\Application;

class Console
{

    private array $commands = [];

    /**
     * @return void
     */
    public function handler(): void
    {
        $application = new Application();

        $this->getDefaultCommand();

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

    private function getDefaultCommand(): void
    {
        $defaultCommand = (new DefaultCommand())->listCommand();
        $this->commands = array_merge($defaultCommand, $this->commands);

    }


}