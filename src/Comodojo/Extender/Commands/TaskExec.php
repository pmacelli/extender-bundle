<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Comodojo\Extender\Commands\Utils\TaskRequestWizard;
use \Comodojo\Extender\Commands\Utils\ResponseVisualizer;
use \Comodojo\Extender\Task\Request;
use \Comodojo\Extender\Task\Table as TasksTable;
use \Comodojo\Extender\Task\Runner;
use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\Foundation\Events\Manager as EventsManager;
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

class TaskExec extends AbstractCommand {

    protected function configure() {

        $this->setName('task:exec')
        ->setDescription('Exec a task')
        ->setHelp('Execute a task directly from commandline (no queue)');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $logger = $this->getLogger();

        $events = EventsManager::create($logger);

        $tasks = TaskTableLoader::load($configuration);
        $table = new TasksTable($configuration, $logger, $events);
        $table->addBulk($tasks);

        $wizard = new TaskRequestWizard(
            $input,
            $output,
            $this->getHelper('question'),
            $configuration,
            $tasks,
            true
        );
        $message = $wizard->start();
        $request = Request::createFromMessage($message);

        $output->write("\nRunning task... ");

        $result = Runner::fastStart(
            $request,
            $configuration,
            $logger,
            $table,
            $events
        );
        $output->write("done!\n\n");

        $visualizer = new ResponseVisualizer(
            $output,
            $this->getHelper('formatter')
        );
        $visualizer->render($result);
        
        return 0;

    }

}
