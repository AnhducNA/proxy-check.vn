<?php

require __DIR__ . '/vendor/autoload.php';

use Anhduc\ProxyCheck\proxy;

$short_options = "h::s::S::H::a::u:f:o:i:t:T:";
$long_options = ["help::", "socks4::", "socks5::", "http::", "all::", "url:",
    "proxies-file:", "output-valid:", "output-invalid:", "thread:","timeout:"];
$options = getopt($short_options, $long_options);
//var_dump($options);
$proxyObject = new proxy();
$proxyObject->setProxyFile("./proxy_list.txt");
$proxyObject->setProxyValidFile("./proxy_valid.txt");
$proxyObject->setProxyInvalidFile("./proxy_invalid.txt");

$proxyObject->setUrlToTest("http://google.fr");
$proxyObject->setThread(5);
$proxyObject->setTimeout(3);
if (isset($options['h']) || isset($options["help"])):
    echo "        
-h, --help                   Show this help
-s, --socks4                 Test socks4 proxies
-S, --socks5                 Test socks5 proxies
-H, --http                   Test http proxies
-a, --a                      Test all proto if no proto is specified in input file
-r, --randomize-file         Shuffle proxies files
-t, --thread=NBR             Number of threads
-T, --timeout=SEC            Set timeout (default 3 seconds)
-u, --url=TARGET             url to test proxies
-f, --proxies-file=FILE      File to check proxy
-m, --max-valid=NBR          Stop when NBR valid proxies are found
-U, --proxies-url=URL        url with proxies file
-p, --dis-progressbar        Disable progress bar
-g, --github                 use github.com/mmpx12/proxy-list
-o, --output-valid=FILE      File to write valid proxies
-i, --output-invalid=FILE    File to write in valid proxies
-v, --version                Print version and exit
    ";
endif;

if (isset($options['s']) || isset($options["socks4"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy($prototype = "socks4");
endif;

if (isset($options['S']) || isset($options["socks5"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy($prototype = "socks5");
endif;

if (isset($options['H']) || isset($options["http"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy($prototype = "http");
endif;
if (isset($options['t']) || isset($options["thread"])) {
    if (isset($options['t'])) {
        $proxyObject->setThread($options['t']);
    } else if (isset($options['thread'])) {
        $proxyObject->setThread($options['thread']);
    }
}

if (isset($options['T']) || isset($options["timeout"])) {
    if (isset($options['t'])) {
        $proxyObject->setTimeout($options['T']);
    } else if (isset($options['timeout'])) {
        $proxyObject->setThread($options['timeout']);
    }
}

if (isset($options['f']) || isset($options["proxyFile"])):
    $proxyObject->setProxyFile($options['f']);
endif;

if (isset($options['o']) || isset($options["output-valid"])):
    $proxyObject->setProxyValidFile($options['o']);
endif;

if (isset($options['i']) || isset($options["output-invalid"])) {
    $proxyObject->setProxyInvalidFile($options['i']);
}



if (isset($options['a']) || isset($options["all"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy();
endif;

if (isset($options['u'])):
    $proxyObject->setUrlToTest($options['u']);
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy();
    echo $options['u'];
endif;