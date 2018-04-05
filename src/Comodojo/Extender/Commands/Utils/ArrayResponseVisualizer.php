<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\FormatterHelper;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;

class ArrayResponseVisualizer {

    protected $output;
    protected $formatter;

    public function __construct(OutputInterface $output, FormatterHelper $formatter) {

        $this->output = $output;
        $this->formatter = $formatter;

    }

    public function render(/*array*/ $result) {

        $table = new Table($this->output);

        $table->setHeaders(
            [
                ['Task uid', $this->formatter->truncate($result['uid'], 8)]
            ]
        )->setRows(
            [
                ['Task class', $result['name']],
                ['Ends in', $result['success'] ? '<info>success</info>' : '<error>error</error>'],
                new TableSeparator(),
                ['Process id (pid)', $result['pid']],
                ['Job id (jid)', $result['jid']],
                ['Worklog id (wid)', $result['wid']],
                new TableSeparator(),
                ['Started at', $result['start']->format('r')],
                ['Finished at', $result['end']->format('r')],
                ['Exec time', $result['end']->diff($result['start'])->format('%hh %mm %ss')],
                new TableSeparator(),
                ['Result', var_export($result['result'], true)]
            ]
        );
        $table->render();

    }

}
