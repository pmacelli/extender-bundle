<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\FormatterHelper;
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

class ArrayResponseVisualizer {

    protected $output;
    protected $formatter;

    public function __construct(OutputInterface $output, FormatterHelper $formatter) {

        $this->output = $output;
        $this->formatter = $formatter;

    }

    public function render(/*array*/ $result) {

        $table = new Table($this->output);

        $table->setHeaders(
            [
                ['Task uid', $this->formatter->truncate($result['uid'], 8)]
            ]
        )->setRows(
            [
                ['Task class', $result['name']],
                ['Ends in', $result['success'] ? '<info>success</info>' : '<error>error</error>'],
                new TableSeparator(),
                ['Process id (pid)', $result['pid']],
                ['Job id (jid)', $result['jid']],
                ['Worklog id (wid)', $result['wid']],
                new TableSeparator(),
                ['Started at', $result['start']->format('r')],
                ['Finished at', $result['end']->format('r')],
                ['Exec time', $result['end']->diff($result['start'])->format('%hh %mm %ss')],
                new TableSeparator(),
                ['Result', var_export($result['result'], true)]
            ]
        );
        $table->render();

    }

}
