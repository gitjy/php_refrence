<?php
//TCP客户端 异步非阻塞
$client = new swoole_client(SWOOLE_TCP | SWOOLE_ASYNC);
//$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
//UDP客户端 异步非阻塞
//$client = new swoole_client(SWOOLE_UDP | SWOOLE_ASYNC);

$client->on("connect", function($cli) {
	echo "connect\n";
    $cli->send("hello world\n");
});

$client->on("receive", function($cli, $data) {
        echo "received: $data\n";
        sleep(1);
        $cli->send("hello\n");
});

$client->on("close", function($cli){
    echo "closed\n";
});

$client->on("error", function($cli){
    exit("error:" . $cli->errCode . socket_strerror($cli->errCode) . "\n");
});

$host = '127.0.0.1';
$port = 9503;
if (!$client->connect($host, $port, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
