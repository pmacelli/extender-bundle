<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;

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

class SchedulesVisualizer {

    const SCHEDULE_ENABLED = '<info>yes</info>';
    const SCHEDULE_DISABLED = '<comment>no</comment>';


    protected $output;

    public function __construct(OutputInterface $output) {

        $this->output = $output;

    }

    public function render(array $schedules) {

        $table = new Table($this->output);

        $table->setHeaders(
            [
                ['Id', 'Name', 'Description', 'Expression', 'Enabled']
            ]
        );
        foreach ($schedules as $schedule) {
            $table->addRow([
                $schedule['id'],
                $schedule['name'],
                $schedule['description'],
                $schedule['expression'],
                $schedule['enabled'] ? self::SCHEDULE_ENABLED : self::SCHEDULE_DISABLED
            ]);
        }

        $table->render();

    }

}
