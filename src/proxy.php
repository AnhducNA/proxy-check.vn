<?php

namespace Anhduc\ProxyCheck;
require 'vendor/autoload.php';

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

class proxy
{
    private string $proxy_valid_file;
    private string $proxy_invalid_file;
    private string $proxy_file;
    private array $proxy_valid;
    private array $proxy_invalid;
    private string $url_to_test;
    private int $thread;
    private int $timeout;

    /**
     * @return string
     */
    public function getProxyValidFile(): string
    {
        return $this->proxy_valid_file;
    }

    /**
     * @param string $proxy_valid_file
     */
    public function setProxyValidFile(string $proxy_valid_file): void
    {
        $this->proxy_valid_file = $proxy_valid_file;
    }

    /**
     * @return string
     */
    public function getProxyInvalidFile(): string
    {
        return $this->proxy_invalid_file;
    }

    /**
     * @param string $proxy_invalid_file
     */
    public function setProxyInvalidFile(string $proxy_invalid_file): void
    {
        $this->proxy_invalid_file = $proxy_invalid_file;
    }

    /**
     * @return string
     */
    public function getProxyFile()
    {
        return $this->proxy_file;
    }

    /**
     * @param string $proxy_file
     */
    public function setProxyFile(string $proxy_file): void
    {
        $this->proxy_file = $proxy_file;
    }

    /**
     * @return array
     */
    public function getProxyValid(): array
    {
        return $this->proxy_valid;
    }

    /**
     * @param array $proxy_valid
     */
    public function setProxyValid(array $proxy_valid): void
    {
        $this->proxy_valid = $proxy_valid;
    }

    /**
     * @return array
     */
    public function getProxyInvalid(): array
    {
        return $this->proxy_invalid;
    }

    /**
     * @param array $proxy_invalid
     */
    public function setProxyInvalid(array $proxy_invalid): void
    {
        $this->proxy_invalid = $proxy_invalid;
    }

    /**
     * @return string
     */
    public function getUrlToTest(): string
    {
        return $this->url_to_test;
    }

    /**
     * @param string $url_to_test
     */
    public function setUrlToTest(string $url_to_test): void
    {
        $this->url_to_test = $url_to_test;
    }

    /**
     * @return int
     */
    public function getThread(): int
    {
        return $this->thread;
    }

    /**
     * @param int $thread
     */
    public function setThread(int $thread): void
    {
        $this->thread = $thread;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function checkProxy($prototype='')
    {
        try {
            $file = file_get_contents($this->proxy_file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->getProxyInvalidFile(), 'a');
            $fh2 = fopen($this->getProxyValidFile(), 'a');
            $client = new Client(['base_uri' => $this->getUrlToTest(), 'timeout' => $this->getTimeout()]);

            $requestGenerator = function ($data) use ($prototype, $client, $fh1, $fh2) {
                foreach ($data as $proxy) {
                    yield $proxy => function () use ($prototype, $client, $proxy, $fh1, $fh2) {
                        if (str_contains($proxy, strtolower($prototype))) {
                            try {
                                $checkBool = 0;
                                // $client->request('GET', '/delay/5', ['connect_timeout' => 3.14]);
                                $result = $client->request('GET', '/', ['proxy' => $proxy]);
                                if ($result == true) {
                                    $checkBool = 1;
                                }
                            } catch (ConnectException|ServerException|RequestException $e) {
                                $checkBool = 0;
                            }
                            if ($checkBool) {
                                echo $proxy . ' => true' . PHP_EOL;
                                fwrite($fh2, $proxy . "\n");
                            } else {
                                echo $proxy . ' => false' . PHP_EOL;
                                fwrite($fh1, $proxy . "\n");

                            }
                        }
                        return $client->getAsync('/get?q=' . $proxy, ['headers' => ['X-Search-Term' => $proxy]]);
                    };
                }
            };
            $pool = new Pool($client, $requestGenerator($data), [
                'concurrency' => $this->getThread(),
                'fulfilled' => function (Response $response, $index) {
                    // This callback is delivered each successful response
                    // $index will be our special identifier we set when generating the request
                    $json = json_decode((string)$response->getBody());
                    // If these values don't match, something is very wrong
                },
                'rejected' => function (Exception $reason, $index) {
                    // This callback is delivered each failed request
                },
            ]);
            // Initiate the transfers and create a promise
            $promise = $pool->promise();
            // Force the pool of requests to complete
            $promise->wait();

            fclose($fh1);
            fclose($fh2);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

}
