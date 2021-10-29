<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\RpcClient\RpcRequest;
use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Comodojo\Extender\Commands\Utils\TaskRequestWizard;
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

class QueueAdd extends AbstractCommand {

    protected function configure() {

        $this->setName('queue:add')
        ->setDescription('Add a task to queue')
        ->setHelp('Add a task to current queue to immediate execution');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $tasks = TaskTableLoader::load($configuration);

        $wizard = new TaskRequestWizard(
            $input,
            $output,
            $this->getHelper('question'),
            $configuration,
            $tasks
        );
        $message = $wizard->start();

        $bridge = new SocketBridge($configuration);
        $uid = $bridge->send(RpcRequest::create("queue.add", [$message->export()]));

        $shortuid = $this->getHelper('formatter')->truncate($uid, 8);
        $output->writeln("Task submitted (uid: $shortuid)");
        
        return 0;

    }

}
