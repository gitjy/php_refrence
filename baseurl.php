<?php

echo "<br/>base64实现方案:<br/>";
$arr = range(0, 63);
$code64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
$code64Arr = str_split($code64); 
foreach ($arr as  $i) {
	$str = chr($i);
	$ord = ord($str);
	$encode = base64_encode($str);
	$codeIndex = base64tobyte($encode);
	$codeBin = base64tobyte($encode, true);
	dump($i,$ord, $str,binmat($i), $encode, $codeBin);
	echo "<br/>";
}

/**
* 将数字格式化为二进制数字字符串
* $i int 值
*/
function binmat($i) {
	return	str_pad(decbin($i),8, '0', STR_PAD_LEFT);
}

/**
* 将字符串格式化二进制数字字符串
* $str string 值
*/
function strbin($str) {
	$bin = '';
	for ($i=0;$i<strlen($str);$i++) {
		$index = ord($str[$i]);
		$bin .=str_pad(decbin($index),8, '0', STR_PAD_LEFT) . ' ';
	}
	return	$bin;
}

/**
* 打印数据 
**/
function dump() {
	$arr = func_get_args();
	foreach ($arr as $v) {
		//var_export($v);
		print_r($v);
		echo "\t\t";
	}
}

/**
* base64编码转字节
**/
function base64tobyte($encode, $bin = false)
{
	$code64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    $code64Arr = str_split($code64);
	$len = strlen($encode);
	$codeIndex = [];
	$codeBin = '';
	for ($k=0;$k<$len;$k++) {
		$codeIndex[] = $s = array_search($encode[$k],$code64Arr);
		$codeBin .=  binmat($s) . ' ';
	}
	if ($bin) {
		return $codeBin;
	}
	return $codeIndex;
}

$str = '##>'; 
$ord = ord($str);
$encode = base64_encode($str);
$bin =strbin($str);
var_dump($ord, $str, $bin, $encode, base64tobyte($encode, true));

$shuffle = ' Pw';
var_dump(base64_decode('Pw'), base64_decode('Pw=='));


