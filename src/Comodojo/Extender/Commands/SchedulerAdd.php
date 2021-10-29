<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Comodojo\Extender\Commands\Utils\ScheduleWizard;
use \Comodojo\Extender\Commands\Utils\SocketBridge;
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

class SchedulerAdd extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:add')
        ->setDescription('Add a schedule')
        ->setHelp('Add a job to current schedule');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $tasks = TaskTableLoader::load($configuration);

        $wizard = new ScheduleWizard(
            $input,
            $output,
            $this->getHelper('question'),
            $configuration,
            $tasks
        );
        list($schedule, $task) = $wizard->start();

        $bridge = new SocketBridge($configuration);
        $id = $bridge->send(RpcRequest::create("scheduler.add", [
            $schedule->export(),
            $task->export()
        ]));

        $output->writeln("Schedule submitted (id: $id)");
        
        return 0;

    }
    
}
