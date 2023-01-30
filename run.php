<?php

/** Library */
require_once('vendor/autoload.php');
//$climate = new League\CLImate\CLImate;
//require_once('src/config.php');
//
$proxy_valid_file = "/home/anhduc/Work/htdocs/proxy-check/proxy_valid.txt";
$proxy_invalid_file = "/home/anhduc/Work/htdocs/proxy-check/proxy_invalid.txt";
//$input_list = $climate->info()->input('File proxy?');
//$proxy_file = $input_list->prompt();
$proxy_file = "/home/anhduc/Work/htdocs/proxy-check/proxy_list.txt";
//$climate->br()->info('Start check your proxy list ..');
echo('Start check proxy list ..' . "\n");
//progress($progress);
try {
    $file = file_get_contents("$proxy_file");
    $data = explode("\n", $file);
    $proxy_valid = [];
    $proxy_invalid = [];
    $i = 0;
    $fh1 = fopen($proxy_invalid_file, 'a');
    $fh2 = fopen($proxy_valid_file, 'a');
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
            $proxy_invalid[$i] = $proxy . "\n";
            $i++;
            fwrite($fh1, $proxy . "\n");
            echo 'false' . "\n";
        } else {
            $proxy_valid[$i] = $proxy . "\n";
            $i++;
            fwrite($fh2, $proxy . "\n");
            echo 'true' . "\n";
        }
    }
    fclose($fh1);
    fclose($fh2);

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
