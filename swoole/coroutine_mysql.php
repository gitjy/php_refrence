<?php
/**
 * actor jiangyong
 * Date: 2019/2/11
 * Time: 下午2:22
 * 使用协程客户端
 */

$http = new swoole_http_server("0.0.0.0", 9501);

$http->on('request', function ($request, $response) {
    $db = new Swoole\Coroutine\MySQL();
    $db->connect([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '123456',
        'database' => 'test',
    ]);
    $data = $db->query('select * from account');
    $response->end(json_encode($data));
});

$http->start();