<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Socket\Messages\Scheduler\Schedule;
use \Comodojo\Extender\Socket\Messages\Task\Request;
use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\ScheduleVisualizer;
use \Comodojo\Extender\Commands\Utils\RequestVisualizer;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;

class SchedulerGet extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:get')
        ->setDescription('Get a schedule')
        ->setHelp('Get schedule informations and details')
        ->addArgument('id_or_name', InputArgument::REQUIRED, 'Id or name of the schedule');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $ion = $input->getArgument('id_or_name');
        $id = intval($ion);

        if ( $id !== 0 ) {
            list($schedule, $request) = $bridge->send(RpcRequest::create("scheduler.get", [$id]));
        } else {
            list($schedule, $request) = $bridge->send(RpcRequest::create("scheduler.get", [$ion]));
        }

        $s_visualizer = new ScheduleVisualizer(
            $output
        );
        $r_visualizer = new RequestVisualizer(
            $output
        );

        $s_visualizer->render(Schedule::createFromExport($schedule));
        $r_visualizer->render(Request::createFromExport($request));

    }

}
