<?php
error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>h2>\n";

$url = 'http://wd.koudai.com/wd/item/getPubInfo';
$param = '{"itemID":"1038417170"}';
$urlInfo = parse_url($url);
$host = $urlInfo['host'];
$path = $urlInfo['path'];
$query = empty($urlInfo['query']) ? '' : $urlInfo['query'];

/* Get the port for the WWW service. */
$service_port = getservbyname('www', 'tcp');

/* Get the IP address for the target host. */
$address = gethostbyname($urlInfo['host']);

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$body = 'param=' . urlencode($param);
$in = "POST $path HTTP/1.1\r\n";
$in .= "Host: $host\r\n";
$in .= "Cache-Control: no-cache\r\n";
$in .= "Connection: Close\r\n";
$in .= "Content-Type: application/x-www-form-urlencoded\r\n";
$in .= "Content-Length: " . strlen($body) . "\r\n\r\n";
$in .= $body;
// $in .= 'param=1';
$out = '';

echo "Sending HTTP GET request..." . PHP_EOL;
echo $in . PHP_EOL;
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";
?>
