<?php
/**
 *  * 协程通信
有了go关键词之后，并发编程就简单多了。与此同时又带来了新问题，如果有2个协程并发执行，另外一个协程，需要依赖这两个协程的执行结果，如果解决此问题呢？
答案就是使用通道（Channel），在Swoole4协程中使用new chan就可以创建一个通道。通道可以理解为自带协程调度的队列。它有两个接口push和pop：
push：向通道中写入内容，如果已满，它会进入等待状态，有空间时自动恢复
pop：从通道中读取内容，如果为空，它会进入等待状态，有数据时自动恢复
 */
//协程模块替换异步客户端
$chan = new chan(2);

# 协程1
go (function () use ($chan) {
    $result = [];
    for ($i = 0; $i < 2; $i++)
    {
        $result += $chan->pop();
    }
    var_dump($result);
});

# 协程2
go(function () use ($chan) {
    $cli = new Swoole\Coroutine\Http\Client('www.qq.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.qq.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $ret = $cli->get('/');
    // $cli->body 响应内容过大，这里用 Http 状态码作为测试
    $chan->push(['www.qq.com' => $cli->statusCode]);
});

# 协程3
go(function () use ($chan) {
    $cli = new Swoole\Coroutine\Http\Client('www.163.com', 80);
    $cli->set(['timeout' => 10]);
    $cli->setHeaders([
        'Host' => "www.163.com",
        "User-Agent" => 'Chrome/49.0.2587.3',
        'Accept' => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $ret = $cli->get('/');
    // $cli->body 响应内容过大，这里用 Http 状态码作为测试
    $chan->push(['www.163.com' => $cli->statusCode]);
});