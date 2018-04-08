<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerDisable extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:disable')
        ->setDescription('disable a schedule')
        ->setHelp('Set the disabled flag of a schedule')
        ->addArgument('id_or_name', InputArgument::REQUIRED, 'Id or name of the schedule to disable');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $ion = $input->getArgument('id_or_name');
        $id = intval($ion);

        if ( $id !== 0 ) {
            $output->write("Disabling job (id: $id)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.disable", [$id]));
        } else {
            $output->write("Disabling job (name: $ion)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.disable", [$ion]));
        }

        $output->write($info ? 'done!' : 'error!');
        $output->writeln('');

    }

}
