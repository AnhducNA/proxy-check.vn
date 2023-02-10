<?php

namespace Anhduc\ProxyCheck;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProxyI
{
    public function __construct(
        public string $name = '',
        public string $protocol = '',
        public ?string $proxy = null,
        public ?bool $is_available = null,
    )
    {
    }

}
