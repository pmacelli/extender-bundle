<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Foundation\Base\Configuration;
use \Comodojo\Foundation\Base\ConfigurationTrait;
use \Comodojo\RpcClient\RpcRequest;
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

class WorklogsRetriever {

    use ConfigurationTrait;

    protected $bridge;

    protected $wklgs = [];

    public function __construct(Configuration $configuration) {

        $this->setConfiguration($configuration);
        $this->bridge = new SocketBridge($configuration);

    }

    public function byId($id, $follow = false) {

        return $this->retrieve(
            RpcRequest::create("worklog.byId", [$id]),
            false,
            $follow
        );

    }

    public function byPid($pid, $follow = false) {

        return $this->retrieve(
            RpcRequest::create("worklog.byPid", [$pid]),
            true,
            $follow
        );

    }

    protected function retrieve(RpcRequest $request, $collection, $follow) {

        $data = $this->bridge->send($request);

        if ( $collection === true ) {

            foreach ($data as $wklg ) {
                $this->wklgs[] = $wklg;
                if ( $follow === true ) $this->follow($wklg);
            }

        } else {

            $this->wklgs[] = $data;
            if ( $follow === true ) $this->follow($data);

        }

        array_multisort(
            array_map(
                function($wk) {
                    return $wk['id'];
                },
                $this->wklgs
            ),
            $this->wklgs
        );

        return $this->wklgs;

    }

    protected function follow($wklg) {

        $this->followChilds($wklg['uid']);
        if ( !empty($wklg['parent_uid']) ) $this->followParents($wklg['parent_uid']);

    }

    protected function followChilds($uid) {

        $wklgs = $this->bridge->send(RpcRequest::create("worklog.byPuid", [$uid]));

        foreach ($wklgs as $wklg) {
            $this->wklgs[] = $wklg;
            $this->followChilds($wklg['uid']);
        }

    }

    protected function followParents($uid) {

        $wklg = $this->bridge->send(RpcRequest::create("worklog.byUid", [$uid]));
        $this->wklgs[] = $wklg;
        if ( !empty($wklg['parent_uid']) ) $this->followParents($wklg['parent_uid']);

    }

}
