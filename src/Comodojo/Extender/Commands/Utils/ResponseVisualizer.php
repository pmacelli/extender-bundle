<?php namespace Comodojo\Extender\Commands\Utils;

// use \Comodojo\Extender\Task\Result;

class ResponseVisualizer extends ArrayResponseVisualizer {

    public function render(/*Result*/ $result) {

        parent::render($result->export());

    }

}
