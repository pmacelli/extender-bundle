<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class WorklogCount extends AbstractCommand {

    protected function configure() {

        $this->setName('worklog:count')
        ->setDescription('Count recorded worklogs')
        ->setHelp('Get the number of executed tasks as recorded in the worklog');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());

        $count = $bridge->send(RpcRequest::create("worklog.count"));

        $output->write("There are <info>$count</info> worklogs recorded so far");
        $output->writeln('');

    }

}
