<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
use \Comodojo\Extender\Commands\Utils\ScheduleSelector;
use \Comodojo\Extender\Commands\Utils\RequestVisualizer;
use \Comodojo\Extender\Socket\Messages\Task\Request;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Symfony\Component\Console\Input\InputInterface;
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

class SchedulerExec extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:exec')
        ->setDescription('Exec a planned schedule')
        ->setHelp('Move a scheduled job to queue to immediate execution');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());

        $schedules = $bridge->send(RpcRequest::create("scheduler.list"));

        $selector = new ScheduleSelector(
            $input,
            $output,
            $this->getHelper('question')
        );
        $schedule = $selector->select($schedules);

        $output->write("\nRetrieving tasks from schedule $schedule...");

        list($schedule, $request) = $bridge->send(RpcRequest::create("scheduler.get", [$schedule]));

        $output->write(" done!\n\n");

        $visual = new RequestVisualizer($output);
        $visual->render(Request::createFromExport($request));

        $output->write("\nTransferring request to queue to immediate execution...");

        $uid = $bridge->send(RpcRequest::create("queue.add", [$request]));

        $shortuid = $this->getHelper('formatter')->truncate($uid, 8);
        $output->writeln(" done! (uid: $shortuid)");
        
        return 0;

    }

}
