<?php
$pattern[] = range('0', '9');
$pattern[] = range('a', 'z');
$pattern = array_merge($pattern[0], $pattern[1]);
shuffle($pattern);
$chunk = array_chunk($pattern, 3);
// $chunk = array_reduce($chunk, function($v, $item) {
//   $v[] = implode('', $item);
//   return $v;
// });
$chunk = array_map(function($item) {
  return implode('', $item);
}, $chunk);
$a = 1;
$bind = ['a' => & $a];
var_dump($bind);
$a = 2;
var_dump($bind);

$arr = [
1 => ['男','女', '不限'],
2 => ['赵', '钱', '孙'],
];
$list = [1,2,1,2,1,2];
foreach ($list as $v) {
	var_dump(array_pop($arr[$v]));
	var_dump($v, $arr[$v]);
}