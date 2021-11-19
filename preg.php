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



function host_replace() {
	echo '<br/>', '域名替换';
	$url = 'https://yd.yds.iyuedan.com/admin/user/video?id=&~id=&uid=2001022150%2C57474655&~uid=&service_id=';
	$host = 'mp4.iyuedan.com';

	echo '<br/>方案一 parse_url+preg <br/>';
	$parseUrl = parse_url($url);
	//print_r($parseUrl);
	$newUrl = preg_replace("!^($parseUrl[scheme]://)($parseUrl[host])!", '$1' . $host,  $url);
	var_dump($newUrl);

	echo '<br/>方案二 直接正则<br/>';
	$newUrl = preg_replace("!^(http[s]?://).*?/!", '$1' . $host . '/',  $url);
	var_dump($newUrl);
}



echo '<br/>\w不包含汉字<br/>';
$str  = '我的爱恋a';
$rs = preg_match('/\w+$/', $str, $match);
var_dump($rs, $match);

echo '<br/>\w /u修饰符包含汉字<br/>';
//$str  = '已123abc_';
$str  = '我的爱恋a';
$rs = preg_match('/\w+$/u', $str, $match);
var_dump($rs, $match);

/*
echo '<br/>拆分字符<br/>',
$str  = '123我的爱恋abc_';
$rs = preg_split('/(?<!^)(?!$)/u', $str);
var_dump($rs, $str);


echo '<br/>拆分字节<br/>';
$rs = str_split($str, 1);
var_dump($rs);*/



echo "<br/><br/>识别字母和数字数量</br>";
$str  = "我1234爱abc她";
$rs = preg_match_all('#[a-zA-Z0-9]#', $str, $match);
var_dump($rs, $match, $str);

echo "<br/>字母和数字检测</br>";
$str  = "我1234爱abc她678";
$rs = preg_match_all('#[[:alnum:]]#', $str, $match);
var_dump($rs, $match, $str);


echo "<br/>\w数字字母替换</br>";
$str  = "我1爱a她你呢...";
$rs = preg_replace('#\w#', '',$str);
var_dump($rs, $str);



echo "<br/>\pL识别字母</br>";
$str = 'abdasd1332';
$rs = preg_match_all('/\pL/', $str, $match);
var_dump($rs, $match);


echo "<br/><br/>违禁中文检测：eregi</br>";
echo '正则表达式的字符编码: ' . mb_regex_encoding() . '</br>"';
//$str = '一二三四五六七八';
$str = 'smile';
$str = mb_convert_encoding($str, 'utf-8');
$word = word();
$rs = mb_eregi($word, $str, $match);
var_dump($rs, $match, $str);


echo "<br/><br/>中文检测：preg</br>";
//$str = '一二三四五六七八';
$word = pregWord();
$rs = preg_match($word, $str, $match);
var_dump($rs, $match, $str);



function word() {
	return '(.*)李(.*)洪(.*)志|sm(?![a-z])|fuck|16dy|18|18岁|18禁|1989年镇压|\w{8,}';
}

function pregWord() {
	return '~16dy|18|18岁|18禁|1989年镇压|\w{8,}~';
}
