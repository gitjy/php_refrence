<?php
/**
 * 设置定时器
 */
//每隔2000ms触发一次
swoole_timer_tick(2000, function ($timer_id) {
    echo "tick-2000ms\n";
});

//3000ms后执行此函数
swoole_timer_after(3000, function () {
    echo "after 3000ms.\n";
});
//异步任务,延迟任务
defer(function () {
    sleep(2);
});