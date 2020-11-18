<?php
$host = '127.0.0.1';
$port = 9504;
//创建Server对象，监听 127.0.0.1:9504端口，类型为SWOOLE_SOCK_UDP
$serv = new swoole_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//swoole server启动服务(start)时事件
$serv->on("start", function ($serv) {
    global $host, $port;
    echo "Swoole websocket server is start at {$host}:{$port}\n";
});

//客户端建立连接
$serv->on('connect', function ($serv, $fd) {
    echo "Client:Connect:{$fd}\n";
});

//监听数据接收事件
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], 'Server ' . $data);
    var_dump($clientInfo);
});

//启动服务器
$serv->start();