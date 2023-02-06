<?php

require './vendor/autoload.php';

use GuzzleHttp\Client;

// Tạo một client với một base URI
$client = new Client(['base_uri' => 'https://reqres.in/api/users?page=2']);

$response = $client->request('GET', '/api/users', [
    'query' => [
        'page' => '2',
    ]
]);
$body = $response->getBody();
$arr_body = json_decode($body);
echo $response->getStatusCode();
var_dump($arr_body);

//$client->request('GET', '/', ['proxy' => 'http://localhost:8125']);
