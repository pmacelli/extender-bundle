<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerRemove extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:remove')
        ->setDescription('Remove a schedule')
        ->setHelp('Remove a schedule from scheduler')
        ->addArgument('id_or_name', InputArgument::REQUIRED, 'Id or name of the schedule to remove');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $ion = $input->getArgument('id_or_name');
        $id = intval($ion);

        if ( $id !== 0 ) {
            $output->write("Removing job (id: $id)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.remove", [$id]));
        } else {
            $output->write("Removing job (name: $ion)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.remove", [$ion]));
        }

        $output->write($info ? 'done!' : 'error!');
        $output->writeln('');

    }

}
