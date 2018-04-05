<?php namespace Comodojo\Extender\Commands\Utils;

class MemoryFormatter {

    protected static $unit = [
        'b',
        'kb',
        'mb',
        'gb',
        'tb',
        'pb'
    ];

    public static function format($mem) {

        return @round($mem/pow(1024,($i=floor(log($mem,1024)))),2).' '.self::$unit[$i];

    }

}
