<?php namespace Comodojo\Extender\Commands\Utils;

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\FormatterHelper;
use \Symfony\Component\Console\Helper\Table;
use \Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;

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

class WorklogsVisualizer {

    const VIEW_COMPACT = 1;
    const VIEW_NORMAL = 2;
    const VIEW_EXTENSIVE = 3;

    protected $output;
    protected $formatter;

    public function __construct(OutputInterface $output, FormatterHelper $formatter) {

        $this->output = $output;
        $this->formatter = $formatter;

    }

    public function render(array $wklgs, $mode = self::VIEW_NORMAL) {

        $table = new Table($this->output);

        switch ($mode) {
            case self::VIEW_COMPACT:
                $this->compactView($table, $wklgs);
                break;
            case self::VIEW_EXTENSIVE:
                $this->extensiveView($table, $wklgs);
                break;
            case self::VIEW_NORMAL:
            default:
                $this->normalView($table, $wklgs);
                break;
        }

        $table->render();

    }

    protected function compactView(Table $table, array $wklgs) {

        $table->setHeaders(
            [
                ['id', 'jid', 'uid', 'puid', 'task', 'status']
            ]
        );
        foreach ($wklgs as $wklg) {
            
            //PHP Fatal error:  Uncaught TypeError: Argument 1 passed to Symfony\Component\Console\Helper\FormatterHelper::truncate() must be of the type string, null given            
            if(is_null($wklg['parent_uid'])) $wklg['parent_uid'] = "";
            
            $table->addRow([
                $wklg['id'],
                $wklg['jid'],
                $this->formatter->truncate($wklg['uid'], 8),
                $this->formatter->truncate($wklg['parent_uid'], 8),
                $wklg['task'],
                WorklogStatusFormatter::format($wklg['status'])
            ]);
        }

    }

    protected function normalView(Table $table, array $wklgs) {

        $table->setHeaders(
            [
                ['id', 'pid', 'jid', 'uid', 'puid', 'task', 'status', 'start', 'end']
            ]
        );
        
        foreach ($wklgs as $wklg) {
            
            //PHP Fatal error:  Uncaught TypeError: Argument 1 passed to Symfony\Component\Console\Helper\FormatterHelper::truncate() must be of the type string, null given            
            if(is_null($wklg['parent_uid'])) $wklg['parent_uid'] = "";
            
            $table->addRow([
                $wklg['id'],
                $wklg['pid'],
                $wklg['jid'],
                $this->formatter->truncate($wklg['uid'], 8),
                $this->formatter->truncate($wklg['parent_uid'], 8),
                $wklg['task'],
                WorklogStatusFormatter::format($wklg['status']),
                date('d.m.y H:i:s', $wklg['start_time']),
                empty($wklg['end_time']) ? 'N/A' : date('d.m.y H:i:s', $wklg['end_time'])
            ]);
        }

    }

    protected function extensiveView(Table $table, array $wklgs) {

        $table->setHeaders(
            [
                ['id', 'pid', 'jid', 'uid', 'puid', 'task', 'status', 'start', 'end']
            ]
        );
        foreach ($wklgs as $wklg) {
            if ( $wklg !== reset($wklgs) ) {
                $table->addRow([
                    new TableSeparator(['colspan' => 9])
                ]);
            }
            $table->addRow([
                $wklg['id'],
                $wklg['pid'],
                $wklg['jid'],
                $this->formatter->truncate($wklg['uid'], 8),
                $this->formatter->truncate($wklg['parent_uid'], 8),
                $wklg['task'],
                WorklogStatusFormatter::format($wklg['status']),
                date('d.m.y H:i:s', $wklg['start_time']),
                empty($wklg['end_time']) ? 'N/A' : date('d.m.y H:i:s', $wklg['end_time'])
            ]);
            $table->addRow([
                new TableSeparator(['colspan' => 9])
            ]);
            $table->addRow([
                "Result",
                new TableCell(var_export($wklg['result'], true), ['colspan' => 8])
            ]);
            if ( $wklg === end($wklgs) ) break;
            $table->addRow([
                new TableSeparator(['colspan' => 9])
            ]);
        }

    }

}
