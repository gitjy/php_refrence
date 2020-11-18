<?php
//array_map 保留数组键名
$a = ['a' =>1, 'b' => 2, 'c' =>3];
$a = array_map(function($item){if($item%2 == 0) return $item;}, $a);
var_dump($a);
