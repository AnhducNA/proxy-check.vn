<?php

namespace Anhduc\ProxyCheck;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class proxy
{
    private string $proxy_valid_file;
    private string $proxy_invalid_file;
    private string $proxy_file;
    private array $proxy_valid;
    private array $proxy_invalid;
    private string $url_to_test;

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

    public function checkProxy()
    {
        try {
            $file = file_get_contents($this->proxy_file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->proxy_invalid_file, 'a');
            $fh2 = fopen($this->proxy_valid_file, 'a');
            foreach ($data as $proxy) {
                $curl = curl_init();
                CURL_SETOPT($curl, CURLOPT_URL, $this->url_to_test);
                CURL_SETOPT($curl, CURLOPT_PROXY, $proxy);
                CURL_SETOPT($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                CURL_SETOPT($curl, CURLOPT_TIMEOUT, 20);
                CURL_SETOPT($curl, CURLOPT_RETURNTRANSFER, True);
                CURL_SETOPT($curl, CURLOPT_FOLLOWLOCATION, True);
                $result = curl_exec($curl);
                curl_close($curl);

                if ($result == false) {
                    fwrite($fh1, $proxy . "\n");
                    echo 'false' . "\n";
                } else {
                    fwrite($fh2, $proxy . "\n");
                    echo 'true' . "\n";
                }
            }
            fclose($fh1);
            fclose($fh2);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function checkProxySocks4()
    {
        try {
            $file = file_get_contents($this->proxy_file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->proxy_invalid_file, 'a');
            $fh2 = fopen($this->proxy_valid_file, 'a');
            foreach ($data as $proxy) {
                if (str_contains($proxy, 'socks4')):
                    $curl = curl_init();
                    CURL_SETOPT($curl, CURLOPT_URL, $this->url_to_test);
                    CURL_SETOPT($curl, CURLOPT_PROXY, $proxy);
                    CURL_SETOPT($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    CURL_SETOPT($curl, CURLOPT_TIMEOUT, 20);
                    CURL_SETOPT($curl, CURLOPT_RETURNTRANSFER, True);
                    CURL_SETOPT($curl, CURLOPT_FOLLOWLOCATION, True);
                    $result = curl_exec($curl);
                    curl_close($curl);

                    if ($result == false) {
                        fwrite($fh1, $proxy . "\n");
                        echo $proxy . ' => false' . "\n";
                    } else {
                        fwrite($fh2, $proxy . "\n");
                        echo $proxy . ' => true' . "\n";
                    }
                endif;
            }
            fclose($fh1);
            fclose($fh2);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function checkProxySocks5()
    {
        try {
            $file = file_get_contents($this->proxy_file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->proxy_invalid_file, 'a');
            $fh2 = fopen($this->proxy_valid_file, 'a');
            foreach ($data as $proxy) {
                if (str_contains($proxy, 'socks5')):
                    $curl = curl_init();
                    CURL_SETOPT($curl, CURLOPT_URL, $this->url_to_test);
                    CURL_SETOPT($curl, CURLOPT_PROXY, $proxy);
                    CURL_SETOPT($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    CURL_SETOPT($curl, CURLOPT_TIMEOUT, 20);
                    CURL_SETOPT($curl, CURLOPT_RETURNTRANSFER, True);
                    CURL_SETOPT($curl, CURLOPT_FOLLOWLOCATION, True);
                    $result = curl_exec($curl);
                    curl_close($curl);

                    if ($result == false) {
                        fwrite($fh1, $proxy . "\n");
                        echo $proxy . ' => false' . "\n";
                    } else {
                        fwrite($fh2, $proxy . "\n");
                        echo $proxy . ' => true' . "\n";
                    }
                endif;
            }
            fclose($fh1);
            fclose($fh2);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function checkProxyHttp()
    {
        try {
            $file = file_get_contents($this->proxy_file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->proxy_invalid_file, 'a');
            $fh2 = fopen($this->proxy_valid_file, 'a');
            $client = new Client(['base_uri' => 'http://httpbin.org/']);
            $client->request('GET', '/delay/5', ['connect_timeout' => 5]);

            foreach ($data as $proxy) {
                if (str_contains($proxy, 'http')):
                    try {
                        $response = $client->request('GET', '/', ['proxy' => $proxy]);
                        if ($response == true) {
                            echo $proxy . ' => true' . "\n";
                            fwrite($fh2, $proxy . "\n");
                        }
                    } catch (RequestException $e) {
                        echo $proxy . ' => false' . "\n";
                        fwrite($fh2, $proxy . "\n");
                        continue;
                    }
                endif;
            }
            fclose($fh1);
            fclose($fh2);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

}
