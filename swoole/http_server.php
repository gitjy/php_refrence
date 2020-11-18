<?php
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on('connect', function ($serv, $fd) {
    echo "Client:Connect:{$fd}\n";
});

$http->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});


//监听数据接收事件
$http->on("request", function ($request, $response) {
    //路由处理
    if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
        return $response->end();
    }
    var_dump($request->get, $request->post);
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
});

$http->start();