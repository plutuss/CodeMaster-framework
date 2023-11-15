<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Console\Commands\Make;

use Plutuss\SauceCore\Console\Commands\TraitMakeCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class MakeController extends Command
{

    use TraitMakeCommand;

    public function defaultContent()
    {
        $this->content = "
<?php

namespace App\Http\Controllers;


class {$this->filename} extends Controller
{

}";

        return $this;

    }

    protected function checkFileExists(): bool
    {
        return file_exists($this->path);
    }

    protected function configure()
    {
        $this->setName('make:controller')
            ->setDescription('Make new controller')
            ->setHelp('Make new controller for your application')
            ->addArgument('name', InputArgument::REQUIRED, 'Pass a name for the controller');
    }

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