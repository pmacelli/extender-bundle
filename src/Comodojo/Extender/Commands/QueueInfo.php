<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\InfoVisualizer;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class QueueInfo extends AbstractCommand {

    protected function configure() {

        $this->setName('queue:info')
        ->setDescription('Get information from queue')
        ->setHelp('Show status and counters about running queue');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $visualizer = new InfoVisualizer(
            $output
        );

        $info = $bridge->send(RpcRequest::create("queue.info"));

        $visualizer->render($info);

    }

}
