<?php
//单位和价格拆分
$unitMoneyList = ['10元/分钟', '', '100.01元'];
foreach ($unitMoneyList as $unitMoney) {
  preg_match('/(\d+(\.\d+)?)(.+)/', $unitMoney, $ret);
  $money[] = $ret;
}
//var_dump($money);


//正则断言
echo '<br/>', '正则断言', '<br/>';
$str = 'hello world!你好，世界';
$pattern = '/(?<!^)(?!$)/u';
$arr = preg_split($pattern, $str);
preg_match_all($pattern, $str, $matches);
var_dump(mb_strlen($str), $arr, $matches);


echo '<br/>';
$n = 'LibArt';
$f = preg_replace('/[A-Z]/', "/$0", $n);
var_dump($f,$n);