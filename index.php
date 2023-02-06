<?php

require __DIR__ . '/vendor/autoload.php';

use Anhduc\ProxyCheck\proxy;


$short_options = "h::s::S::H::u:f:";
$long_options = ["help::", "socks4::", "socks5::", "http::", "url=TARGET:", "proxies-file:"];
$options = getopt($short_options, $long_options);
//var_dump($options);
$proxyObject = new proxy();
$proxyObject->setProxyFile("/home/anhduc/Work/htdocs/proxy-check/proxy_list.txt");
$proxyObject->setProxyValidFile("/home/anhduc/Work/htdocs/proxy-check/proxy_valid.txt");
$proxyObject->setProxyInvalidFile("/home/anhduc/Work/htdocs/proxy-check/proxy_invalid.txt");

if (isset($options['f']) || isset($options["proxyFile"])):
    $proxyObject->setProxyFile($options['f']);
endif;
if (isset($options['h']) || isset($options["help"])):
    echo "        -h, --help                   Show this help
        -s, --socks4                 Test socks4 proxies
        -S, --socks5                 Test socks5 proxies
        -H, --http                   Test http proxies
        -i, --ip                     Test all proto if no proto is specified in input file
        -r, --randomize-file         Shuffle proxies files
        -t, --thread=NBR             Number of threads
        -T, --timeout=SEC            Set timeout (default 3 seconds)
        -u, --url=TARGET             url to test proxies
        -f, --proxies-file=FILE      File to check proxy
        -m, --max-valid=NBR          Stop when NBR valid proxies are found
        -U, --proxies-url=URL        url with proxies file
        -p, --dis-progressbar        Disable progress bar
        -g, --github                 use github.com/mmpx12/proxy-list
        -o, --output=FILE            File to write valid proxies
        -v, --version                Print version and exit
    ";
endif;

if (isset($options['s']) || isset($options["socks4"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxySocks4();
endif;

if (isset($options['S']) || isset($options["socks5"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxySocks5();
endif;
if (isset($options['h']) || isset($options["http"])):
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxySocks5();
endif;


if (isset($options['u'])):

    $proxyObject->setUrlToTest($options['u']);
    echo('Start check proxy list ..' . "\n");
    $proxyObject->checkProxy();
    echo $options['u'];
endif;