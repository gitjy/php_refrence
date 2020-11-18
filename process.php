<?php

//pcntl多进程实现
function mulit($type = 0)
{
	$num = 1;
	$str = "EasySwoole,Easy学swoole\n";

	if (1 == $type) {
		$pid = pcntl_fork();//新开一个子进程,上面的变量内存将会复制一份到子进程中.这个函数,在主进程中返回子进程进程id,在子进程返回0,开启失败在主进程返回-1
	} else {
		$process = new swoole_process(function () use ($str) {//实例化一个进程类,传入回调函数
		    echo $str;//变量内存照常复制一份,只不过swoole的开启子进程后使用的是回调方法运行
		    echo "我是子进程,我的pid是" . getmypid() . "\n";
	    });
		$pid = $process->start();//开启子进程,创建成功返回子进程的PID，创建失败返回false。
	}
	//这下面的代码,将会被主进程,子进程共同执行
	echo $str;

	if (1 == $type) {
		if($pid>0){//主进程代码
		    echo "我是主进程,子进程的pid是{$pid}\n";
		}elseif($pid==0){
		    echo "我是子进程,我的pid是".getmypid()."\n";
		}else{
		    echo "我是主进程,我现在慌得一批,开启子进程失败了\n";
		}
	} else {
		if ($pid > 0) {//主进程代码
	    	echo "我是主进程,子进程的pid是{$pid}\n";
		}else{
		    echo "我是主进程,我现在不慌了,失败就失败吧\n";
		}	
	}

}

mulit();
