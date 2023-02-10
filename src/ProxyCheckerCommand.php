<?php

namespace Anhduc\ProxyCheck;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
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
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $proxies = $this->loadProxiesFromFile('');

        $client = new Client(['timeout' => 10]);
        $uri = 'https://ipinfo.io/json';

        $proxies->map(function (Proxy $proxy) use ($client, $uri, $output) {
            $output->writeln($proxy->proxy);
            try {
                $client->request('GET', $uri, ['proxy' => $proxy->name]);
                $proxy->is_available = true;
            } catch (GuzzleException $e) {
                $proxy->is_available = false;
            }
        });

        dd($proxies);

        //save valid to file

        //save invalid to file
    }

    public function loadProxiesFromFile(string $path) {
        $proxies = [
            '23.254.100.211:8800',
            '104.227.229.216:8800',
            '23.254.10.184:8800',
        ];
        $collection = new Collection([]);
        foreach ($proxies as $proxy) {
            $proxy = new Proxy($proxy, 'http', $proxy);
            $collection->add($proxy);
        }

        return $collection;
    }

    public function saveProxiesToFile(Collection $proxies, string $path) {

    }
}