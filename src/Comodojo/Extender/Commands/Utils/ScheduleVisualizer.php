<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Socket\Messages\Scheduler\Schedule;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;

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

class ScheduleVisualizer {

    protected $output;

    public function __construct(OutputInterface $output) {
        $this->output = $output;
    }

    public function render(Schedule $schedule) {

        $table = new Table($this->output);
        $table->setStyle('borderless');
        $table->setHeaders(
            [
                ['Name', $schedule->getName()]
            ]
        )->setRows(
            [
                ['Description', $schedule->getDescription()],
                ['Expression', $schedule->getExpression()],
                ['Enabled', $schedule->getEnabled() ? '<info>Yes</info>' : '<comment>No</comment>']
            ]
        );
        $table->render();

    }

}
