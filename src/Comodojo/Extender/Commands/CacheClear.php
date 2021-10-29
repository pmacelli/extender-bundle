<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Comodojo\SimpleCache\Manager as SimpleCacheManager;
use \Comodojo\Foundation\Logging\Manager as LogManager;

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

class CacheClear extends AbstractCommand {

    protected function configure() {

        $this->setName('cache:clear')
        ->setDescription('Clear extender cache')
        ->setHelp('This command will clear the whole extender cache');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();

        $logger = LogManager::create('console', false)->getLogger();
        $manager = SimpleCacheManager::createFromConfiguration($configuration, $logger);

        $output->write('Clearing cache... ');
        $manager->clear();
        $output->write('done!');
        $output->writeln('');
        
        return 0;

    }

}
