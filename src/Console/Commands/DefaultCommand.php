<?php

declare(strict_types=1);

namespace Plutuss\CodeMaster\Console\Commands;

use Plutuss\CodeMaster\Console\Commands\Make\MakeController;

class DefaultCommand
{


    public function listCommand(): array
    {
        return [
            MakeController::class,
        ];
    }


}