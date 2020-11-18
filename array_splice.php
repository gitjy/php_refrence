<?php

//遍历数据 截取数据
echo "splice 移除数字键名，保留字符串键名";
//$input = array_combine(range(1000,1100),range(1000,1100));
$input = array_combine(range('a','e'),range('a','e'));
while($list = array_splice($input, 0, 10)) {
	var_dump($list);
	var_dump($input);
}


$input = range(1, 2);
$cell = ['hello', 'do'];
$replace[] = $cell;
//添加一个元素

var_dump(array_splice($input,2, 0, [$cell]), $input);