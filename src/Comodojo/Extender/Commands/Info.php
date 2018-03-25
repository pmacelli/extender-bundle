<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\Extender\Components\Version;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class Info extends AbstractCommand {

    protected function configure() {

        $this->setName('info')
        ->setDescription('Show dispatcher informations')
        ->setHelp('Show general information about dispatcher instance');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $version = new Version($configuration);

        $output->writeln([
            $version->getDescription(),
            '-----------------------------'
        ]);

    }

}
