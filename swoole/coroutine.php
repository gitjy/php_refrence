<?php
/**
 * 协程并发
 * Date: 2019/2/11
 * Time: 下午3:00
 * go ：创建一个协程
    chan ：创建一个通道
    defer ：延迟任务，在协程退出时执行，先进后出
 *
 */
/**
 * time php coroutine.php
 * bc
real	0m2.152s
user	0m0.033s
sys	0m0.038s
 */

Swoole\Runtime::enableCoroutine();

go(function ()
{
    sleep(1);
    echo "b";
});

go(function ()
{
    sleep(2);
    echo "c";
});
