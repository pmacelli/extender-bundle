<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerEnable extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:enable')
        ->setDescription('Enable a schedule')
        ->setHelp('Set the enabled flag of a schedule')
        ->addArgument('id_or_name', InputArgument::REQUIRED, 'Id or name of the schedule to enable');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $ion = $input->getArgument('id_or_name');
        $id = intval($ion);

        if ( $id !== 0 ) {
            $output->write("Enabling job (id: $id)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.enable", [$id]));
        } else {
            $output->write("Enabling job (name: $ion)... ");
            $info = $bridge->send(RpcRequest::create("scheduler.enable", [$ion]));
        }

        $output->write($info ? 'done!' : 'error!');
        $output->writeln('');

    }

}
