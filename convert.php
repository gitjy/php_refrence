<?php
//超出长度的16进制字符串
$hexadecimal = '706870e4ba8ce6aca1e5bc80e58f91efbc9a7777772e706870322e6363';
$binNum = base_convert($hexadecimal, 16, 2);
var_dump($binNum);

//这里bin 是指二进制字符串，不是二进制数字
$binStr = hex2bin($hexadecimal);
var_dump($binStr);
