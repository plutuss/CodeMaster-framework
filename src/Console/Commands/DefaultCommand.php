<?php

declare(strict_types=1);

namespace Plutuss\SauceCore\Console\Commands;

use Plutuss\SauceCore\Console\Commands\Make\MakeController;

class DefaultCommand
{

    public function listCommand()
    {
        return [
            MakeController::class,
        ];
    }


}