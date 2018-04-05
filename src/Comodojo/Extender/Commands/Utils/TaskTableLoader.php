<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Components\TasksLoader;
use \Comodojo\Foundation\Base\Configuration;
use \Exception;

class TaskTableLoader {

    public static function load(Configuration $configuration) {

        $base_path = $configuration->get('base-path');
        $tasks_file = "$base_path/config/comodojo-tasks.yml";

        if ( !file_exists($tasks_file) ) {
            throw new Exception("Cannot read tasks file $tasks_file");
        }

        return TasksLoader::load($tasks_file);

    }

}
