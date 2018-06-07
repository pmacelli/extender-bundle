<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Socket\Messages\Scheduler\Schedule;
use \Comodojo\Extender\Socket\Messages\Task\Request;
use \Symfony\Component\Console\Question\Question;
use \Symfony\Component\Console\Question\ConfirmationQuestion;
use \Cron\CronExpression;
use \RuntimeException;

/**
 * @package     Comodojo Extender (default bundle)
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
 * @license     MIT
 *
 * LICENSE:
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class ScheduleWizard extends TaskRequestWizard {

    public function start() {

        $this->output->writeln([
            "*** Starting schedule-request wizard ***",
            ""
        ]);

        $schedule = $this->startScheduleWizard();

        $task = $this->startTaskWizard();

        if ($this->askScheduleVisualResumeConfirmation($schedule, $task) !== true) {
            throw new RuntimeException('Aborted by the user');
        }

        return [$schedule, $task];

    }

    protected function startScheduleWizard() {

        $schedule_name = $this->askUserForName();
        $schedule_description = $this->askUserForDescription();
        $schedule_expression = $this->askUserForExpression();

        $enabled = $this->simple === false ? $this->askUserForEnable() : true;

        // create new schedule
        $schedule = new Schedule();
        $schedule->setName($schedule_name)
            ->setDescription($schedule_description)
            ->setExpression($schedule_expression)
            ->setEnabled($enabled);

        return $schedule;

    }

    protected function askUserForName() {

        $prompt = $this->getPrompt("Name of schedule?");
        $question = new Question($prompt);
        $question->setValidator(function($name) {
            if ( preg_match('/^[a-zA-Z0-9\-\_]+$/', $name) === false ) {
                throw new RuntimeException(
                    'The name should contain only chars, numbers, minus and underscore'
                );
            }
            return $name;
        });

        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askUserForDescription() {

        $prompt = $this->getPrompt("Brief description (opt)");
        $question = new Question($prompt, null);
        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askUserForExpression() {

        $prompt = $this->getPrompt("Cron expression");
        $question = new Question($prompt);
        $question->setValidator(function($exp) {
            if ( !CronExpression::isValidExpression($exp) ) {
                throw new RuntimeException(
                    'Invalid cron expression'
                );
            }
            return $exp;
        });
        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askUserForEnable() {

        $question = new ConfirmationQuestion("Enable schedule? (y)>", true);

        return $this->helper->ask($this->input, $this->output, $question);

    }

    protected function askScheduleVisualResumeConfirmation(Schedule $schedule, Request $request) {

        $visual = new ScheduleVisualizer($this->output);
        $visual->render($schedule);

        return $this->askVisualResumeConfirmation($request);

    }

}
