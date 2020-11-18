<?php
$host = "127.0.0.1";
$port = 9503;
$fds = [];
$server = new swoole_server($host, $port);

//swoole server启动服务(start)时事件
$server->on("start", function ($server) {
    global $host, $port;
    echo "Swoole websocket server is start at {$host}:{$port}\n" . 'PHP进程的id '. getmypid() . "\n";
    echo "管理进程的PID" . $server->manager_pid . "\n";
    echo "主进程的PID" . $server->master_pid . "\n";
    //$serv->manager_pid;  //管理进程的PID，通过向管理进程发送SIGUSR1信号可实现柔性重启
    //$serv->master_pid;  //主进程的PID，通过向主进程发送SIGTERM信号可安全关闭服务器
});

//swoole server启动工作进程时事件
$server->on("WorkerStart", function ($server, $worker_id) {
    echo "workstart id {$worker_id} \n";
    echo 'PHP进程的id '. getmypid() . "\n";
});

//监听连接进入事件
$server->on('connect', function ($server, $fd) {
    echo "Client:Connect: {$fd}\n";
    //$fds 虽然是全局变量，但只在当前的进程内有效。 客户端使用sleep
    //多进程共享数据 Swoole服务器底层会创建多个Worker进程，在var_dump($fds)打印出来的值，只有部分连接的fd。
    global $fds;
    $fds[] = $fd;
    var_dump('PHP进程的id '. getmypid(),$fds);
});

//监听数据接收事件
$server->on('receive', function ($server, $fd, $reactor_id, $data) {
    echo "receive connection_id: {$fd}\n";
    echo "receive data: {$data}\n";
    $server->send($fd, "Swoole: {$data}");
    $server->close($fd);    //关闭客户端连接
});
$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});
$server->start();