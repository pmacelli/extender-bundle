<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Foundation\Base\Configuration;
use \Comodojo\Foundation\Base\ConfigurationTrait;
use \Comodojo\Extender\Socket\Messages\Task\Request;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\HelperInterface;
use \Symfony\Component\Console\Question\Question;
use \Symfony\Component\Console\Question\ConfirmationQuestion;
use \Symfony\Component\Console\Question\ChoiceQuestion;
use \RuntimeException;

class TaskRequestWizard {

    use ConfigurationTrait;

    protected $input;

    protected $output;

    protected $helper;

    protected $tasks;

    protected $prompt = [];

    protected $simple = false;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        HelperInterface $helper,
        Configuration $configuration,
        array $tasks,
        $simple = false
    ) {

        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
        $this->setConfiguration($configuration);
        $this->tasks = $tasks;
        $this->simple = (bool) $simple;

    }

    public function start() {

        $this->output->writeln([
            "*** Starting task-request wizard ***",
            ""
        ]);

        $output = $this->startTaskWizard();

        if ($this->askVisualResumeConfirmation($output) !== true) {
            throw new RuntimeException('Aborted by the user');
        }

        return $output;

    }

    protected function startTaskWizard() {

        list($name, $class) = $this->askUserForTask();
        $params = $this->askUserForParams();

        if ( $this->simple === false ) {
            $niceness = $this->askUserForNiceness();
            $maxtime = $this->askUserForMaxTime();
            $pipe = $this->askUserForChain('pipe');
            $done = $this->askUserForChain('onDone');
            $fail = $this->askUserForChain('onFail');
        } else {
            $niceness = 0;
            $maxtime = 600;
            $pipe = null;
            $done = null;
            $fail = null;
        }

        return Request::create($name, $class, $params)
                ->setNiceness($niceness)
                ->setMaxtime($maxtime)
                ->pipe($pipe)
                ->onDone($done)
                ->onFail($fail);
    }

    protected function askUserForTask() {

        $question = new ChoiceQuestion(
            'Select a task to execute',
            self::filterTasks($this->tasks)
        );
        $question->setErrorMessage('Task %s is invalid.');
        $question->setPrompt($this->getPrompt());

        $selected = $this->helper->ask($this->input, $this->output, $question);

        $this->prompt[] = "<info>[$selected]</info>";
        return [$selected, self::selectTask($this->tasks, $selected)];

    }

    protected function askUserForNiceness() {

        $prompt = $this->getPrompt("Niceness? (0)");
        $question = new Question($prompt, '0');
        $question->setValidator(function($niceness) {
            if ((20 <= $niceness) || ($niceness >= 20)) {
                throw new RuntimeException(
                    'The niceness should be a integer in range (-20,+20)'
                );
            }
            return $niceness;
        });

        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askUserForMaxTime() {

        $default = $this->getConfiguration()->get('child-max-runtime');

        $prompt = $this->getPrompt("Max exec time? ($default secs)");
        $question = new Question($prompt, $default);
        $question->setValidator(function($secs) {
            if ( !is_int($secs) || $secs <= 0) {
                throw new RuntimeException(
                    'Maxtime should be an integer > 0'
                );
            }
            return $secs;
        });

        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askUserForParams() {

        $prompt = $this->getPrompt("Additional parameters (as an encoded string)");
        $question = new Question($prompt);

        parse_str($this->helper->ask($this->input, $this->output, $question), $params);

        return $params;

    }

    protected function askUserForChain($type) {

        $prompt = $this->getPrompt("Do you want to chain another request ($type)? (n)");
        $question = new ConfirmationQuestion($prompt, false);

        if ( !$this->helper->ask($this->input, $this->output, $question) ) return null;

        $this->prompt[] = "<comment>($type)</comment>";
        $piped = $this->startTaskWizard();

        array_pop($this->prompt);
        array_pop($this->prompt);

        return $piped;

    }

    protected static function filterTasks(array $tasks) {
        return array_keys($tasks);
        // return array_map(function($task) {
        //     return $task['name'];
        // }, $tasks);
    }

    protected static function selectTask($tasks, $name) {
        return $tasks[$name]['class'];
        // foreach ($tasks as $task) {
        //     if ($task['name'] === $name) {
        //         return $task['class'];
        //     }
        // }
    }

    protected function getPrompt($question = null) {
        return "#".implode(":", $this->prompt)." $question> ";
    }

    protected function askVisualResumeConfirmation(Request $request) {

        $visual = new RequestVisualizer($this->output);
        $visual->render($request);

        $question = new ConfirmationQuestion("Confirm and submit request? (y)>", true);

        return $this->helper->ask($this->input, $this->output, $question);

    }

}
