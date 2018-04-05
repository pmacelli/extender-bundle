<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
use \Exception;

class TaskList extends AbstractCommand {

    protected function configure() {

        $this->setName('task:list')
        ->setDescription('Get a list of registered tasks')
        ->setHelp('Get the current list of registered tasks');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $tasks = TaskTableLoader::load($this->getConfiguration());

        $table = new Table($output);
        $table->setHeaders(
            [
                ['Name', 'Class', 'Description']
            ]
        );
        foreach ($tasks as $task) {
            $table->addRow([$task['name'], $task['class'], $task['description']]);
        }

        $table->render();

    }

}
