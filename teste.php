<?php

use GuzzleHttp\Client;

require_once 'vendor/autoload.php';

$client = new Client();

$resposta1 = $client->get('http://localhost:8080/http-server.php');
$resposta2 = $client->get('http://localhost:8000/http-server.php');

echo 'R1 '. $resposta1->getBody()->getContents() .PHP_EOL;
echo 'R2 ' . $resposta2->getBody()->getContents() .PHP_EOL;