<?php
$server = new swoole_websocket_server("127.0.0.1", 9502);

//swoole server启动服务(start)时事件
$server->on("start", function ($server) {
    echo "Swoole websocket server is start\n";
});

//客户端建立连接
$server->on('connect', function ($serv, $fd){
    echo "Client:Connect:{$fd}\n";
});

//websocket客户端建立连接时事件
$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

//webSocket接收消息
$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));	//webSocket发送消息
    //$server->push($frame->fd, 'hello world');	//webSocket发送消息
});

//TCP接收消息
$server->on('receive', function ($server, $fd, $reactor_id, $data) {
     echo "receive connection: {$fd}\n";
     echo "receive data: {$data}\n";
    $server->send($fd, "Swoole: {$data}");	//发送TCP消息
    $server->close($fd);
});

//HTTP 接收请求
$server->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

//客户端关闭连接时
$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();