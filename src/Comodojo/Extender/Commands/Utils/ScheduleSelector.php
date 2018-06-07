<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\HelperInterface;
use \Symfony\Component\Console\Question\ChoiceQuestion;

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

class ScheduleSelector {

    protected $input;

    protected $output;

    protected $helper;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        HelperInterface $helper
    ) {

        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;

    }

    public function select(array $schedules) {

        $question = new ChoiceQuestion(
            'Select a schedule',
            self::filterSchedules($schedules)
        );
        $question->setErrorMessage('Schedule %s is invalid.');

        $selected = $this->helper->ask($this->input, $this->output, $question);

        return $selected;

    }

    protected function filterSchedules(array $schedules) {

        return array_map(function($schedule) {
            return $schedule['name'];
        }, $schedules);

    }

}
