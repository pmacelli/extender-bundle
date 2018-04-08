<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\SchedulesVisualizer;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerList extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:list')
        ->setDescription('List of scheduled tasks')
        ->setHelp('Get a list of scheduled tasks')
        ->addOption(
            'compact',
            'c',
            InputOption::VALUE_NONE,
            'Show a compact view'
        );

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());

        $schedules = $bridge->send(RpcRequest::create("scheduler.list"));

        $visualizer = new SchedulesVisualizer(
            $output
        );

        $visualizer->render($schedules);

    }

}
