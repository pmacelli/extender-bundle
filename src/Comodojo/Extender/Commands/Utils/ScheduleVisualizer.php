<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Socket\Messages\Scheduler\Schedule;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;

class ScheduleVisualizer {

    protected $output;

    public function __construct(OutputInterface $output) {
        $this->output = $output;
    }

    public function render(Schedule $schedule) {

        $table = new Table($this->output);
        $table->setStyle('borderless');
        $table->setHeaders(
            [
                ['Name', $schedule->getName()]
            ]
        )->setRows(
            [
                ['Description', $schedule->getDescription()],
                ['Expression', $schedule->getExpression()],
                ['Enabled', $schedule->getEnabled() ? '<info>Yes</info>' : '<comment>No</comment>']
            ]
        );
        $table->render();

    }

}
