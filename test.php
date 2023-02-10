<?php

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

require 'vendor/autoload.php';
$data = ['http://42.98.75.138:80', 'http://42.98.75.138:80', 'http://42.98.75.138:80', 'http://42.98.75.138:80',
    'http://5.9.87.30:9100', 'http://43.155.69.95:8080', 'http://43.155.69.95:8080', 'http://43.245.219.106:8080',
    'http://8.210.83.33:80', 'http://8.213.128.6:8080', 'http://43.204.16.38:80'];

$client = new GuzzleHttp\Client(['base_uri' => 'http://httpbin.org', 'timeout' => 2.0]);
//foreach ($data as $proxy) {
//    try {
//        $checkBool = 0;
////        $client->request('GET', '/delay/5', ['connect_timeout' => 3.14]);
//        $result = $client->request('GET', '/', ['proxy' => $proxy]);
////        var_dump($result);
//        if ($result == true) {
//            $checkBool = 1;
//        }
//    } catch (ConnectException|ServerException|RequestException $e) {
//        $checkBool = 0;
//    }
//    if ($checkBool) {
//        echo $proxy . ' => true' . PHP_EOL;
//    } else {
//        echo $proxy . ' => false' . PHP_EOL;
//    }
//}
//$data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
$requestGenerator = function ($data) use ($client) {
    foreach ($data as $proxy) {
        // The magic happens here, with yield key => value
        yield $proxy => function () use ($client, $proxy) {
            // Our identifier does not have to be included in the request URI or headers
            try {
                $checkBool = 0;
                $result = $client->request('GET', '/', ['proxy' => $proxy]);
//        var_dump($result);
                if ($result == true) {
                    $checkBool = 1;
                }
            } catch (ConnectException|ServerException|RequestException $e) {
                $checkBool = 0;
            }
            if ($checkBool) {
                echo $proxy . ' => true' . PHP_EOL;
            } else {
                echo $proxy . ' => false' . PHP_EOL;
            }
            return $client->getAsync('/get?q=' . $proxy, ['headers' => ['X-Search-Term' => $proxy]]);
        };
    }
};
$pool = new GuzzleHttp\Pool($client, $requestGenerator($data), [
    'concurrency' => 5,
    'fulfilled' => function (GuzzleHttp\Psr7\Response $response, $index) {
        // This callback is delivered each successful response
        // $index will be our special identifier we set when generating the request
        $json = json_decode((string)$response->getBody());

        // If these values don't match, something is very wrong
//        echo "Requested search term: ", $index, "\n";
//        echo "Parsed from response: ", $json->headers->{'X-Search-Term'}, "\n\n";
    },
    'rejected' => function (Exception $reason, $index) {
        // This callback is delivered each failed request
//        echo "Requested search term: ", $index, "\n";
//        echo $reason->getMessage(), "\n\n";
    },
]);
// Initiate the transfers and create a promise
$promise = $pool->promise();
// Force the pool of requests to complete
$promise->wait();


