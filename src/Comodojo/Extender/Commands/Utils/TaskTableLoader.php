<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Extender\Components\TasksLoader;
use \Comodojo\Foundation\Base\Configuration;
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

class TaskTableLoader {

    public static function load(Configuration $configuration) {

        $base_path = $configuration->get('base-path');
        $tasks_file = "$base_path/config/comodojo-tasks.yml";

        if ( !file_exists($tasks_file) ) {
            throw new Exception("Cannot read tasks file $tasks_file");
        }

        return TasksLoader::load($tasks_file);

    }

}
