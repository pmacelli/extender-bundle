<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Extender\Commands\Utils\SocketBridge;
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

class SchedulerRefresh extends AbstractCommand {

    protected function configure() {

        $this->setName('scheduler:refresh')
        ->setDescription('Refresh scheduler')
        ->setHelp('Force the scheduler to rebuild plans ');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $bridge = new SocketBridge($this->getConfiguration());

        $output->write('Refreshing plans... ');
        $result = $bridge->send(RpcRequest::create("scheduler.refresh"));
        $output->write($result ? "done!" : "failed ($result)");
        $output->writeln('');
        
        return 0;

    }

}
