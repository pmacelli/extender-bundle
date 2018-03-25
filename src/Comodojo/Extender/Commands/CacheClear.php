<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Comodojo\SimpleCache\Manager as SimpleCacheManager;
use \Comodojo\Foundation\Logging\Manager as LogManager;

class CacheClear extends AbstractCommand {

    protected function configure() {

        $this->setName('cache:clear')
        ->setDescription('Clear dispatcher cache')
        ->setHelp('This command will clear the whole dispatcher cache');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();

        $logger = LogManager::create('console', false)->getLogger();
        $manager = SimpleCacheManager::createFromConfiguration($configuration, $logger);

        $output->write('Clearing cache... ');
        $manager->clear();
        $output->write('done!');
        $output->writeln('');

    }

}
