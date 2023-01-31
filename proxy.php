<?php

namespace src;

class proxy
{
    private string $proxy_valid_file;
    private string $proxy_invalid_file;
    private string $proxy_file;
    private array $proxy_valid;
    private array $proxy_invalid;

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

    public function checkProxy(){
        try {
            $file = file_get_contents($this->proxy_file);
            var_dump($file);
            $data = explode("\n", $file);
            $fh1 = fopen($this->proxy_invalid_file, 'a');
            $fh2 = fopen($this->proxy_valid_file, 'a');
            foreach ($data as $proxy) {
                $curl = curl_init();
                CURL_SETOPT($curl, CURLOPT_URL, "http://google.fr");
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

}
