<?php

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsServer;
use \Ds\Set;
use React\Socket\ConnectorInterface;

require_once 'vendor/autoload.php';

$chatComponent = new class implements MessageComponentInterface{
    private Set $connections;

    public function __construct()
    {
        $this->connections = new Set();
    }
    
    public function onOpen(ConnectionInterface $conn)
    {
        echo "Abre conexão";
        $this->connections->add($conn);
    }
    public function onClose(ConnectionInterface $conn)
    {
        echo "Encerra Conexão";
        $this->connections->remove($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "Erro: " . $e->getTraceAsString();
    }

    public function onMessage(ConnectionInterface $from, MessageInterface $msg)
    {
        foreach($this->connections as $connection){
            if($connection !== $from){
                $connection->send((string)$msg);
            }
        }
    }
};

$server = IoServer::factory(
    new HttpServer(
        New WsServer($chatComponent)
    ),
    8002
);

$server->run();