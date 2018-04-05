<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Comodojo\Extender\Commands\Utils\TaskRequestWizard;
use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;

class QueueAdd extends AbstractCommand {

    protected function configure() {

        $this->setName('queue:add')
        ->setDescription('Add a task to queue')
        ->setHelp('Add a task to current queue to immediate execution');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $tasks = TaskTableLoader::load($configuration);

        $wizard = new TaskRequestWizard(
            $input,
            $output,
            $this->getHelper('question'),
            $configuration,
            $tasks
        );
        $message = $wizard->start();

        $bridge = new SocketBridge($configuration);
        $uid = $bridge->send(RpcRequest::create("queue.add", [$message->export()]));

        $shortuid = $this->getHelper('formatter')->truncate($uid, 8);
        $output->writeln("Task submitted (uid: $shortuid)");

    }

}
