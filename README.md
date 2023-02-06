# PROXY-CHECK

Check if proxies are working (http, socks4 & socks5)

![](.github/.screenshot/proxy-check.png)

### usage:

```
-h, --help                   Show this help
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
```
- Check proxy http, ví dụ : 
```
 php index.php -f /home/anhduc/Work/htdocs/proxy-check/proxy_list.txt -H
```

- Check proxy socks4, ví dụ :

```
php index.php -f /home/anhduc/Work/htdocs/proxy-check/proxy_list.txt   -s
```


- Check proxy socks5, ví dụ :

```
php index.php -f /home/anhduc/Work/htdocs/proxy-check/proxy_list.txt -S
```
- Check proxy với url, ví dụ : 

```
php index.php -f /home/anhduc/Work/htdocs/proxy-check/proxy_list.txt   -u http://google.fr 
```