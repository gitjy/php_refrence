<?php
include 'websocketClient.php';

/*$client = new websocket_client();
$client->on("open",function ($client) {
    $fd = $client->getTcpClient()->sock;
    echo "fd: $fd is open\n";
    $msg = [
        "path" => "/index/index/index",
        "data" => "hhh"
    ];
    $client->send(json_encode($msg));
});
$client->on("message", function ($client, $frame) {
    $fd = $client->getTcpClient()->sock;
    echo "fd: $fd received: {$frame->data}\n";
});
$client->on("close", function ($client) {
    $fd = $client->getTcpClient()->sock;
    echo "fd: $fd is closed\n";
});
$client->connect("127.0.0.1", 9502);*/

//阻塞模式
$client = new websocket_client(false);
//连接到服务器
if (!$client->connect("127.0.0.1", 9502))
{
    die("connect failed.\n");
}
$client->send("hello world\n");
$str = $client->recv();
echo $str;