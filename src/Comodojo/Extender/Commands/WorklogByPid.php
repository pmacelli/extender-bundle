<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\WorklogsVisualizer;
use \Comodojo\Extender\Commands\Utils\WorklogsRetriever;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Output\OutputInterface;
use \Exception;

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

class WorklogByPid extends AbstractCommand {

    protected function configure() {

        $this->setName('worklog:byPid')
        ->setDescription('Get worklogs by pid')
        ->setHelp('Retrieve worklogs information using pid as key')
        ->addArgument('pid', InputArgument::REQUIRED, 'Pid of the worklog to visualize')
        ->addOption(
            'follow',
            'f',
            InputOption::VALUE_NONE,
            'Follow task chain'
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

        $pid = (int) $input->getArgument('pid');
        $follow = $input->getOption('follow');

        $retriever = new WorklogsRetriever($this->getConfiguration());
        $visualizer = new WorklogsVisualizer(
            $output,
            $this->getHelper('formatter')
        );

        $wklgs = $retriever->byPid($pid, $follow);

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
