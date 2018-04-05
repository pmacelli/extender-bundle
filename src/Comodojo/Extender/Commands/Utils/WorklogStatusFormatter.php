<?php namespace Comodojo\Extender\Commands\Utils;

class WorklogStatusFormatter {

    protected static $statuses = [
        "RUNNING",
        "<error>ERROR</error>",
        "<info>COMPLETED</info>",
        "<comment>ABORTED</comment>"
    ];

    public static function format($status) {

        return self::$statuses[$status];

    }

}
