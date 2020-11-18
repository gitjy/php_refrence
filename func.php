<?php

//任何用于函数内部的变量按缺省情况将被限制在局部函数范围内。

//静态变量（static variable）
//static 不仅可以用在类中
//变量 $a 仅在第一次调用 test() 函数时被初始化，之后每次调用 test() 函数都会输出 $a 的值并加一。
//和全局变量不同的是$count在函数结束是自动unset;
function test()
{
    static $count = 0;

    $count++;
    echo $count;
    if ($count < 10) {
        test();
    }
    $count--;
}

test();
//echo $count; //Notice: Undefined variable


echo "\n";

//变量作用域

/**
闭包可以从父作用域中继承变量。 任何此类变量都应该用 use 语言结构传递进去。
这些变量都必须在函数或类的头部声明。 
函数 头部声明  use 语言结构
从父作用域中继承变量与使用全局变量是不同的。
全局变量存在于一个全局的范围，无论当前在执行的是哪个函数。
而闭包的父作用域是定义该闭包的函数
*/

//直接调用匿名函数
function Hello () 
{
	return (function () {
		return 'Hello';
	})();

}

var_dump(Hello());

//匿名函数自动binding $this
class Test
{
	public $a = 1;
	public $fnc;
	public function __construct() {
		 function() {
			return $this->a;
		};
	}


}

$t = new Test();


//匿名函数目前是通过 Closure 类来实现的。闭包函数也可以作为变量的值来使用。PHP 会自动把此种表达式转换成内置类 Closure 的对象实例。
$ayfunc = function () {};

var_dump($ayfunc);

//闭包函数的绑定
$ayfunc = function () { echo $this->a;};


$cl = $ayfunc->bindTo(new Test);

$cl();

echo "\n";

$cl = $ayfunc->bind($ayfunc, new Test);

$cl();

echo "\n";

var_dump($ayfunc->bind($ayfunc, new Test)());








