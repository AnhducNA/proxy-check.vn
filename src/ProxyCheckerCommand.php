<?php

namespace Anhduc\ProxyCheck;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProxyCheckerCommand extends Command
{
    //-h, --help                   Show this help
    //-s, --socks4                 Test socks4 proxies
    //-S, --socks5                 Test socks5 proxies
    //-H, --http                   Test http proxies
    //-a, --all                    Test all proto if no proto is specified in input file
    //-r, --randomize-file         Shuffle proxies files
    //-t, --thread=NBR             Number of threads
    //-T, --timeout=SEC            Set timeout (default 3 seconds)
    //-u, --url=TARGET             url to test proxies
    //-f, --proxies-file=FILE      File to check proxy
    //-m, --max-valid=NBR          Stop when NBR valid proxies are found
    //-U, --proxies-url=URL        url with proxies file
    //-p, --dis-progressbar        Disable progress bar
    //-g, --github                 use github.com/mmpx12/proxy-list
    //    -o, --output-valid=FILE      File to write valid proxies
    //-i, --output-invalid=FILE    File to write in valid proxies
    //-v, --version                Print version and exit

    protected function configure()
    {
        $this
            ->setDefinition(
                new InputDefinition([
                    new InputOption('socks4', 's', null, 'Test socks4 proxies'),
                    new InputOption('socks5', 'S', null, 'Test socks5 proxies'),
                    new InputOption('http', 'H', null, 'Test http proxies'),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}