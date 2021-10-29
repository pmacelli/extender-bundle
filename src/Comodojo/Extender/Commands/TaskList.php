<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\Extender\Commands\Utils\TaskTableLoader;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;
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

class TaskList extends AbstractCommand {

    protected function configure() {

        $this->setName('task:list')
        ->setDescription('Get a list of registered tasks')
        ->setHelp('Get the current list of registered tasks');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $tasks = TaskTableLoader::load($this->getConfiguration());

        $table = new Table($output);
        $table->setHeaders(
            [
                ['Name', 'Class', 'Description']
            ]
        );
        foreach ($tasks as $task) {
            $table->addRow([$task['name'], $task['class'], $task['description']]);
        }

        $table->render();
        
        return 0;

    }

}
