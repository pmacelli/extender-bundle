<?php namespace Comodojo\Extender\Commands\Utils;

use \Comodojo\Foundation\Base\Configuration;
use \Comodojo\Foundation\Base\ConfigurationTrait;
use \Comodojo\Daemon\Socket\SocketTransport;
use \Comodojo\RpcClient\RpcClient;
use \Comodojo\RpcClient\RpcRequest;
use \Exception;

class SocketBridge {

    use ConfigurationTrait;

    protected $client;

    public function __construct(Configuration $configuration) {

        $this->setConfiguration($configuration);

        $socket_addr = $configuration->get('sockethandler');
        $transport = SocketTransport::create($socket_addr);
        $this->client = new RpcClient($socket_addr, null, $transport);

    }

    public function send(RpcRequest $request) {

        try {

            $this->client->addRequest($request);

            return $this->client->send();

        } catch (Exception $e) {

            throw $e;

        }

    }

}
