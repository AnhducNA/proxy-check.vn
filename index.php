<?php

require 'vendor/autoload.php';

use src\proxy;

$proxyObject = new proxy();
$proxyObject->setProxyFile("/home/anhduc/Work/htdocs/proxy-check/proxy_list.txt");
$proxyObject->setProxyValidFile("/home/anhduc/Work/htdocs/proxy-check/proxy_valid.txt");
$proxyObject->setProxyInvalidFile("/home/anhduc/Work/htdocs/proxy-check/proxy_invalid.txt");

echo('Start check proxy list ..' . "\n");

$proxyObject->checkProxy();