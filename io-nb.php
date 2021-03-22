<?php

//Lista de Mensagens
$streamList = [
    fopen('arquivo1.txt','r'),
    fopen('arquivo2.txt','r')
];  

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
        echo fgets($stream);
        unset($streamList[$key]);
    }

}while(!empty($streamList));

echo "Li todos os Arquivos" .PHP_EOL; 