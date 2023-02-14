<?php

namespace Anhduc\ProxyCheck;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
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

    protected static $defaultName = 'check';

    protected function configure()
    {
        $this
            ->setDefinition(
                new InputDefinition([
                    new InputOption('socks4', 's', null, 'Test socks4 proxies'),
                    new InputOption('socks5', 'S', null, 'Test socks5 proxies'),
                    new InputOption('http', 'H', null, 'Test http proxies'),
                    new InputOption('all', 'a', null, 'Test all proto if no proto is specified in input file'),
                    new InputOption('timeout', 'T', InputOption::VALUE_REQUIRED, 'Set timeout (default 3 seconds)'),
                    new InputOption('url', 'u', InputOption::VALUE_REQUIRED, 'Url to test proxies'),
                    new InputOption('proxies-file', 'f', InputOption::VALUE_REQUIRED, 'File to check proxy'),
                    new InputOption('output-valid', 'o', InputOption::VALUE_REQUIRED, 'File to write valid proxies'),
                    new InputOption('output-invalid', 'i', InputOption::VALUE_REQUIRED, 'File to write invalid proxies'),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('socks4')) {
            $protocol = 'socks4';
        } else if ($input->getOption('socks5')) {
            $protocol = 'socks5';
        } else if ($input->getOption('http')) {
            $protocol = 'http';
        } else {
            $protocol = '';
        }
        $timeout = ($input->getOption('timeout')) ? $input->getOption('timeout') : 3;
        $uri = ($input->getOption('url')) ? $input->getOption('url') : 'https://ipinfo.io/json';
        $urlGetProxies = ($input->getOption('proxies-file')) ? $input->getOption('proxies-file') : './proxy_list.txt';
        $outputValidFile = ($input->getOption('output-valid')) ? $input->getOption('output_valid') : './proxy_valid.txt';
        $outputInvalidFile = ($input->getOption('output-invalid')) ? $input->getOption('output_invalid') : './proxy_invalid.txt';
        $fh1 = fopen($outputValidFile, 'a');
        $fh2 = fopen($outputInvalidFile, 'a');


        $proxies = $this->loadProxiesFromFile($urlGetProxies);
        $client = new Client(['timeout' => $timeout]);
        $proxies->map(function (Proxy $proxy) use ($fh1, $fh2, $client, $uri, $output, $protocol) {
            if ($protocol == $proxy->protocol) {
                $output->writeln($proxy->proxy);
                try {
                    $client->request('GET', $uri, ['proxy' => $proxy->name]);
                    $proxy->is_available = true;
                    fwrite($fh2, $proxy->proxy . PHP_EOL);
                } catch (GuzzleException $e) {
                    $proxy->is_available = false;
                    fwrite($fh1, $proxy->proxy . PHP_EOL);
                }
//                echo $proxy->protocol;
            }
        });
        fclose($fh1);
        fclose($fh2);
    }

    public function loadProxiesFromFile(string $path)
    {
        $file = file_get_contents($path);
        $proxies = explode("\n", $file);
        $collection = new Collection([]);
        foreach ($proxies as $proxy) {
            if (str_contains($proxy, 'socks4')) {
                $protocol = 'socks4';
            } else if (str_contains($proxy, 'socks5')) {
                $protocol = 'socks5';
            } else if (str_contains($proxy, 'http')) {
                $protocol = 'http';
            } else {
                $protocol = '';
            }
            $proxy = new Proxy($proxy, $protocol, $proxy);
            $collection->add($proxy);
        }
        return $collection;
    }

    public function saveProxiesToFile(Collection $proxies, string $path)
    {

    }
}