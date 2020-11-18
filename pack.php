<?php
  
/** 
* 将字符串转换成二进制 
* @param type $str 
* @return type 

* php 中显示的字符串是多少进制的？？
* 例如：$str = '你好'; // 这边的 '你好' 是什么进制数据（我知道他是字符串！）??
*/  
function StrToConvert($str, $convert = 'hex', $mb = true){  
    //1.列出每个字符
    // 这边的分割正则也不理解
    // (?<!^) 后瞻消极断言
    // (?!$) 前瞻消极断言
    // 看意思好像说的是：不以^开头（但是这边 ^ 又没有被转义...），不以 $ 结尾（同上）
    // 然后得到的记过就是字符串一个个被分割成了数组（郁闷）
    // 求解释

    if ($mb) {
    	$cnt = mb_strlen($str);
    	var_dump($cnt, strlen($str));
    	for($i=0;$i< $cnt;$i++) {
    		$arr[] = mb_substr($str,$i, 1);
    	}
    } else {
    	 $arr = preg_split('/(?<!^)(?!$)/u', $str); 
    }
    
    //$arr = str_split($str); 
    //2.unpack字符  
    foreach($arr as &$v){
        /**
         * unpack：将二进制字符串解包(英语原文：Unpack data from binary string)
         * H: 英语描述原文：Hex string, high nibble first 
         * 这段代码做了什么？？
         */
        $temp = unpack('H*', $v); // 这边被解析出来的字符串为什么是 16进制的？？
        $v = $temp[1];
        if ('bin' == $convert) {
        	$v = base_convert($temp[1], 16, 2); 
        }
        unset($temp);  
    }  
  
    return join(' ',$arr);  
}  
  
/** 
* 讲二进制转换成字符串 
* @param type $str 
* @return type 
*/  
function BinToStr($str){  
    $arr = explode(' ', $str);  
    foreach($arr as &$v){  
        $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));  
    }  
  
    return join('', $arr);  
}  

   /*
     * 16进制转普通字符串
     */

    function hexToStr($hex)
    {
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $str;
    }
  


$str = "Hello World！你好，世界";
echo StrToConvert($str, 'bin');
echo '<br/>';  
echo StrToConvert($str, 'hex', true);
echo '<br/>';  
echo BinToStr(StrToConvert($str, 'bin'));
/*
echo BinToStr("1001000 1100101 1101100 1101100 1101111 100000 1010111 1101111 1110010 1101100 1100100 111011111011110010000001 111001001011110110100000 111001011010010110111101 111011111011110010001100 111001001011100010010110 111001111001010110001100");  

echo '<br/>';  
echo "<br/>","源字符串",
var_dump($str);
echo '<br/>', '<br/>';  
*/
//将字符串转换为16进制
//$hexstr = bin2hex($str);
$hexstr = '169feb687c2b9ebb92a4e47d7010749bd17bc56fbbb5b410f7603d1cd36a219e8c63a83c076b9508a60e4bea86b9535bcfe4785f42e5179c60f737fda0623e46c275a303b26a8789dfc7f8a224d9426e75ae2f1132c3a2a46c51a4e26b609fac44c8e7767f32531e108821292c4edde323f1c8b9dbe2f866901a65508f0c6089';
$bin = hex2bin($hexstr);

echo  "<br/>","16进制字符串";
var_dump(StrToConvert($bin, 'bin'), $hexstr);
echo  "<br/>","转换回来的字符串","<br/>";
var_dump($bin, pack('H*', $hexstr));

$hex = '0102030405060708';
var_dump(hexToStr($hex) == hex2bin($hex));


