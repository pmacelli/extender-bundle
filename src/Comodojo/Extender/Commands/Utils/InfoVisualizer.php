<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;

class InfoVisualizer {

    protected $output;

    public function __construct(OutputInterface $output) {

        $this->output = $output;

    }

    public function render(array $info) {

        $table = new Table($this->output);

        $table->setHeaders(
            [
                ['Worker Pid', $info['USAGE']['PID']]
            ]
        )->setRows(
            [
                ['Started at', date('r', $info['USAGE']['STARTTIMESTAMP'])],
                ['Memory usage (avg)', MemoryFormatter::format($info['USAGE']['MEMORYUSAGE'])],
                ['Memory usage (peak)', MemoryFormatter::format($info['USAGE']['MEMORYPEAKUSAGE'])],
                new TableSeparator(),
                ['Queued tasks', $info['COUNTERS']['QUEUED']],
                ['Running tasks', '<comment>'.$info['COUNTERS']['RUNNING'].'</comment>'],
                ['Completed tasks', $info['COUNTERS']['COMPLETED']],
                ['Succeeded tasks', '<info>'.$info['COUNTERS']['SUCCEEDED'].'</info>'],
                ['Failed tasks', '<fg=red>'.$info['COUNTERS']['FAILED'].'</>'],
                ['Aborted tasks', '<fg=red>'.$info['COUNTERS']['ABORTED'].'</>']
            ]
        );

        $table->render();

    }

}
