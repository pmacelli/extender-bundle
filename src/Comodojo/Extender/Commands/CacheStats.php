<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\SimpleCache\Manager as SimpleCacheManager;
use \Comodojo\Foundation\Logging\Manager as LogManager;
use \Comodojo\Cache\Components\EnhancedCacheItemPoolStats;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;

class CacheStats extends AbstractCommand {

    protected function configure() {

        $this->setName('cache:stats')
        ->setDescription('Get cache statistics')
        ->setHelp('This command shows statistics about cache providers');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();

        $logger = LogManager::create('console', false)->getLogger();
        $manager = SimpleCacheManager::createFromConfiguration($configuration, $logger);

        $stats = $manager->getStats();

        foreach ($stats as $stat) {
            self::printStats($stat, $output);
        }

    }

    protected static function printStats(EnhancedCacheItemPoolStats $stats, OutputInterface $output) {

        $table = new Table($output);
        $table->setHeaders(
            [
                ['Provider', $stats->getProvider()]
            ]
        )->setRows(
            [
                ['Status', $stats->getStats() === 0 ? 'ACTIVE' : 'ERROR'],
                ['Objects', $stats->getObjects()]
            ]
        );
        $table->render();


    }

}
