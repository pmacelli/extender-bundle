<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\WorklogsVisualizer;
use \Comodojo\Extender\Socket\Messages\Worklog\Filter;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

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

class WorklogList extends AbstractCommand {

    protected function configure() {

        $this->setName('worklog:list')
        ->setDescription('List last worklogs')
        ->setHelp('Get a list of last worklogs with a brief task resume')
        ->addOption(
            'limit',
            'l',
            InputOption::VALUE_REQUIRED,
            'Number of worklogs to display',
            10
        )
        ->addOption(
            'offset',
            'o',
            InputOption::VALUE_REQUIRED,
            'Offset to start from',
            0
        )
        ->addOption(
            'reverse',
            'r',
            InputOption::VALUE_NONE,
            'Start from the oldest worklog'
        )
        ->addOption(
            'compact',
            'c',
            InputOption::VALUE_NONE,
            'Show a compact view'
        )
        ->addOption(
            'extensive',
            'e',
            InputOption::VALUE_NONE,
            'Show an extensive view'
        );

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());
        $filter = Filter::createFromExport([
            "limit" => $input->getOption('limit'),
            "offset" => $input->getOption('offset'),
            "reverse" => $input->getOption('reverse')
        ]);

        $wklgs = $bridge->send(RpcRequest::create("worklog.list", [$filter->export()]));

        $visualizer = new WorklogsVisualizer(
            $output,
            $this->getHelper('formatter')
        );

        if ( $input->getOption('extensive') ) {
            $mode = WorklogsVisualizer::VIEW_EXTENSIVE;
        } else if ( $input->getOption('compact') ) {
            $mode = WorklogsVisualizer::VIEW_COMPACT;
        } else {
            $mode = WorklogsVisualizer::VIEW_NORMAL;
        }

        $visualizer->render($wklgs, $mode);
        
        return 0;

    }

}
