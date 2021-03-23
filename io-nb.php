<?php

//Lista de Mensagens
$streamList = [
    stream_socket_client('tcp://localhost:8080'), //retorna um stream
    fopen('arquivo1.txt','r'),
    fopen('arquivo2.txt','r')
];  
fwrite($streamList[0],'GET /http-server.php HTTP/1.1'.PHP_EOL.PHP_EOL);
foreach($streamList as $stream){
    stream_set_blocking($stream,false);
}

//arquivo pronto pra ler?
do{
    $copyReadStream = $streamList;
    $numeroDeStreams = stream_select($copyReadStream,$write,$except,0,0);

    if($numeroDeStreams === 0 ){
        continue;
    }

    foreach($copyReadStream as $key => $stream){
        $conteudo = stream_get_contents($stream);
        $posicaoFimHttp = strpos($conteudo,"\r\n\r\n");
        if($posicaoFimHttp !== false){
            echo substr($conteudo,$posicaoFimHttp + 4);
        }else{
            echo $conteudo;
        }
        unset($streamList[$key]);
    }

}while(!empty($streamList));

echo "Li todos os Arquivos" .PHP_EOL; 