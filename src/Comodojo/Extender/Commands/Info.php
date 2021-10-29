<?php namespace Comodojo\Extender\Commands;

use \Comodojo\Foundation\Console\AbstractCommand;
use \Comodojo\Extender\Components\Version;
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

class Info extends AbstractCommand {

    protected function configure() {

        $this->setName('info')
        ->setDescription('Show dispatcher informations')
        ->setHelp('Show general information about dispatcher instance');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $configuration = $this->getConfiguration();
        $version = new Version($configuration);

        $output->writeln([
            $version->getDescription(),
            '-----------------------------'
        ]);
        
        return 0;

    }

}
