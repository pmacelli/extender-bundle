<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerRefresh extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:refresh')
        ->setDescription('Refresh scheduler')
        ->setHelp('Force the scheduler to rebuild plans ');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());

        $output->write('Refreshing plans... ');
        $result = $bridge->send(RpcRequest::create("scheduler.refresh"));
        $output->write($result ? "done!" : "failed ($result)");
        $output->writeln('');

    }

}
