<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\Console\Commands\Make;

use Plutuss\CodeMaster\Console\Commands\TraitMakeCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class MakeController extends Command
{

    use TraitMakeCommand;

    /**
     * @return $this
     */
    public function defaultContent(): static
    {
        $this->content =
            "<?php

namespace App\Http\Controllers;


class {$this->filename} extends Controller
{

}";

        return $this;

    }

    /**
     * @return bool
     */
    protected function checkFileExists(): bool
    {
        return file_exists($this->path);
    }

    protected function configure(): void
    {
        $this->setName('make:controller')
            ->setDescription('Make new controller')
            ->setHelp('Make new controller for your application')
            ->addArgument('name', InputArgument::REQUIRED, 'Pass a name for the controller');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filename = $input->getArgument('name');
        $this->defaultContent();
        $this->getPath('/Http/Controllers/');

        if ($this->checkFileExists()) {
            $output->writeln(sprintf("The file $this->filename exists"));
            return Command::INVALID;
        }

        $this->createFile();
        $output->writeln(sprintf('Create a new -  %s', $input->getArgument('name')));
        return Command::SUCCESS;
    }


}