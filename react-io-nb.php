<?php

use React\EventLoop\Factory;
use React\Stream\DuplexResourceStream;
use React\Stream\ReadableResourceStream;

require_once 'vendor/autoload.php';

$loop = Factory::create();

$file = fopen('arquivo1.txt','r');

//$stream = new ReadableResourceStream($file,$loop);

$streamList = [
    new ReadableResourceStream(stream_socket_client('tcp://localhost:8081'),$loop), //retorna um stream
    New ReadableResourceStream(fopen('arquivo1.txt','r'),$loop),
    New ReadableResourceStream(fopen('arquivo2.txt','r'),$loop)
];

/*$readableStreamList = array_map(
    fn($stream) => new ReadableResourceStream($stream,$loop),
    $streamList
);*/
$http = new DuplexResourceStream(stream_socket_client('tcp://localhost:8001'),$loop);
$http->write('GET /http-server.php HTTP/1.1'."\r\n\r\n");
$http->on('data',function($data){
    $posicaoFimHttp = strpos($data,"\r\n\r\n");
    echo substr($data,$posicaoFimHttp + 4).PHP_EOL;
}); 

foreach($streamList as $readableStream){
    $readableStream->on('data',function($data){
        echo $data .PHP_EOL;
    });
    
}

$loop->run();