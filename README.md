# http-revers-proxy-server
Http revers proxy server designed to hide internal servers

1. Placce all files in document root folder on your proxy server
2. Change config file (config.php): add whitelisted domain names, define your internal server
3. Use already creaeted or create your own .htaccess to redirect all requests on your index.php script
4. To catch POSTed data on your internal server use php io stream ```file_get_contents("php://input");```
