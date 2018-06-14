<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Socket\Messages\Task\Request;
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

class RequestVisualizer {

    protected $visual_items = [];

    protected $output;

    public function __construct(OutputInterface $output) {
        $this->output = $output;
    }

    public function render(Request $request) {

        $table = new Table($this->output);
        $table->setStyle('borderless');
        $this->getVisualItemOutput($request);
        $table->setRows($this->visual_items);
        $table->render();

    }

    protected function getVisualItemOutput(Request $request, $pad = null, $level = 0) {

        $pattern = "<info>name</info>: %s\n".
            "<info>niceness</info>: %d\n".
            "<info>maxtime</info>: %d\n".
            "<info>params</info>: %s";

        $item = [
            sprintf($pattern,
                $request->getName(),
                $request->getNiceness(),
                $request->getMaxtime(),
                var_export($request->getParameters(), true)
            )
        ];

        $pads = empty($pad) ? [] : [$pad];
        $levels = $level <= 1 ? [] : array_fill(0, $level-1, '');
        $this->visual_items[] = array_merge($levels, $pads, $item);

        $pipe = $request->getPipe();
        if ($pipe instanceof Request) {
            $this->visual_items[] = new TableSeparator();
            $this->getVisualItemOutput($pipe, '<comment>=[ pipe ]=></comment>', $level+1);
        }

        $done = $request->getOnDone();
        if ($done instanceof Request) {
            $this->visual_items[] = new TableSeparator();
            $this->getVisualItemOutput($done, '<info>=[ done ]=></info>', $level+1);
        }

        $fail = $request->getOnFail();
        if ($fail instanceof Request) {
            $this->visual_items[] = new TableSeparator();
            $this->getVisualItemOutput($fail, '<fg=red>=[ fail ]=></>', $level+1);
        }

    }

}
