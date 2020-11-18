<?php
//客户端阻塞模式
$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if (!$client->connect('127.0.0.1', 9503, 0.5))
{
    die("connect failed.\n");
}
sleep(30);
//向服务器发送数据
if (!$client->send("hello world"))
{
    die("send failed.\n");
}
//从服务器接收数据
$data = $client->recv();
if (!$data)
{
    die("recv failed.\n");
}
echo $data . "\n";
//关闭连接
$client->close();