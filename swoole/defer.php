<?php
/**
 * 协程并发
 * Date: 2019/2/11
 * Time: 下午3:00
 * go ：创建一个协程
    chan ：创建一个通道
    defer ：延迟任务，在协程退出时执行，先进后出
 *
 * 协程通信
有了go关键词之后，并发编程就简单多了。与此同时又带来了新问题，如果有2个协程并发执行，另外一个协程，需要依赖这两个协程的执行结果，如果解决此问题呢？
答案就是使用通道（Channel），在Swoole4协程中使用new chan就可以创建一个通道。通道可以理解为自带协程调度的队列。它有两个接口push和pop：
push：向通道中写入内容，如果已满，它会进入等待状态，有空间时自动恢复
pop：从通道中读取内容，如果为空，它会进入等待状态，有数据时自动恢复
 */

//defer延迟任务
Swoole\Runtime::enableCoroutine();
go(function () {
echo "a";
defer(function () {
    echo "~a";
});
echo "b";
defer(function () {
    echo "~b";
});
sleep(1);
echo "c";
});