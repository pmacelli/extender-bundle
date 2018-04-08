<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;

class SchedulesVisualizer {

    const SCHEDULE_ENABLED = '<info>yes</info>';
    const SCHEDULE_DISABLED = '<comment>no</comment>';


    protected $output;

    public function __construct(OutputInterface $output) {

        $this->output = $output;

    }

    public function render(array $schedules) {

        $table = new Table($this->output);

        $table->setHeaders(
            [
                ['Id', 'Name', 'Description', 'Expression', 'Enabled']
            ]
        );
        foreach ($schedules as $schedule) {
            $table->addRow([
                $schedule['id'],
                $schedule['name'],
                $schedule['description'],
                $schedule['expression'],
                $schedule['enabled'] ? self::SCHEDULE_ENABLED : self::SCHEDULE_DISABLED
            ]);
        }

        $table->render();

    }

}
