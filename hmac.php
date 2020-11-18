<?php
$data = 'http://www.iyuedan.com';
$SecretKey = 'secret';
$str  = hash_hmac("sha1", $data, $SecretKey);

var_dump($str, "<br/>");
$str1 = hmacsha1($SecretKey, $data);
var_dump($str1);

//自定义加密算法
function hmacsha1($key,$data) {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
    return bin2hex($hmac);
}
