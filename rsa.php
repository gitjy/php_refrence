<?php


/**
格式化公/私钥
*/
$str = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9YETGP775ZZbwdzQorPR0HFDY39IpwbImlK6N+foZytYwxdaVeDBPhWSOSWyG4PoBKcDVXnEiYMsjmULR8Y07Y2ZVzrIf+d1xNpjkIGb90KIEnfmFu91uUsBoy1afc9z94oneDY21/W0ufu+QfsC/6dBEFpCYpM3FkGjDBZeGRQIDAQAB';


class Rsa {
	static  $alg = OPENSSL_ALGO_MD5;

	static $publicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCht0vnroDAgYocdUiaI+uf1nu5RxINDi19P2v87tapx3a0VvFOU0RCeRYhzC2HXKw5ELw1p9GPRyVfk+OBeaQVxdw1y0Opl80HrQJAeLzbAG3IGD3qXclFiUkJPJURVjl/Fg+VsH9RdU7QGON95KK1f9wu/H53n+Z2PwIWC54/GwIDAQAB';

	static $privateKey = 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKG3S+eugMCBihx1SJoj65/We7lHEg0OLX0/a/zu1qnHdrRW8U5TREJ5FiHMLYdcrDkQvDWn0Y9HJV+T44F5pBXF3DXLQ6mXzQetAkB4vNsAbcgYPepdyUWJSQk8lRFWOX8WD5Wwf1F1TtAY433korV/3C78fnef5nY/AhYLnj8bAgMBAAECgYEAkZLB9n3kkuZJYFLHl1Hsgob9+wwaGLyBgHS+DgMmI9FVbwOw1xQnpzr/DE+TUH3A3uHMuvQltbeS0hX7v4qzhCB2y0rv3/kzH+l8ZVG0f5d8EZ6JaAD3BIIv8m1CKNKsMvb4P42s0payZ7t7N2Zs88YxF3o75oBd4r9E6zp2INECQQDoaVmjfGiX5c4ezs2sxXpmidRDKP90LOEsfwYigKS4HLm7P/J/75cMNmMUllZPN/9KIyJMgL14KiqXvFy26s9zAkEAsiEYOwOSPrXnPVuHCzEC5bNLfSuyZkn+kJKsjpWfrw/RzJwQ+/m94BtB8MiATaEikTFOs1o8Do6TXEoyFskXuQJAJv+OiA1XK63nEWpYPtaAPHaCRKa7fs4NFr9V0zJM8Yv4aoBHe8pDSUxTAeBcKTzXPKR6m+W6ZVCGByIXWKY8GwJBAJHyC6hOhMRcxdm/5CWHQphxDqi/5KtqdOh/RGKOtQb6Lys1s7TAKpIzwxNFM9pm66uKerD4fbxvlM1I/UEuAgECQDHNqx1C1rxuNLkCDX7HzFneyo7cBlfpP7idSBqlh0bBjgjlNVw2LuFzoJeKIRwmz7DWxybkkKVQ7sBQ/CZcbX4=';

		 /**
     * rsa私钥加签
     * @param $keys
     */
    static function sign($str, $key = null, $alg = null)
    {
        //读取私钥文件
        $key = self::formatKey(self::$privateKey);
        $privkeyId = openssl_pkey_get_private($key);
        //$deail = openssl_pkey_get_details($privkeyId);
        //var_dump($deail);
        $result = openssl_sign($str,
         $signature, 
         $privkeyId, 
         self::$alg);
        openssl_free_key($privkeyId);
        return $signature;
        //return base64_encode($signature);
    }

	 /**
     * rsa公钥验签
     * @param $keys
     */
    static function verify($str, $signature, $key = null, $alg = null)
    {
        //读取密钥文件
        $key = self::formatKey(self::$publicKey, 1);
        $keyId = openssl_pkey_get_public($key);
        $result = openssl_verify($str,
         $signature, 
         $keyId, 
         self::$alg);
        openssl_free_key($keyId);
        return $result === 1 ? true : false;
        //return base64_encode($signature);
    }

    static function encrypt($data, $key = null)
    {
    	$key = self::formatKey(self::$publicKey, 1);
        $keyId = openssl_pkey_get_public($key);
        $maxlength = self::getMaxEncryptBlockSize($keyId);
        $output = '';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            openssl_public_encrypt($input, $out, $keyId);
            $output .= $out;
        }
        openssl_free_key($keyId);
        //$output = bin2hex($output);
        return $output;
    }

    static function decrypt($data, $key = null)
    {
    	$key = self::formatKey(self::$privateKey);
        $keyId = openssl_pkey_get_private($key);
        $maxlength = self::getMaxDecryptBlockSize($keyId);
        $output = '';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            openssl_private_decrypt($input, $out, $keyId);
            $output .= $out;
        }
        openssl_free_key($keyId);
        return $output;
    }


    static function encryptByPri($data, $key = null)
    {
    	$key = self::formatKey(self::$privateKey);
        $keyId = openssl_pkey_get_private($key);
        $maxlength = self::getMaxEncryptBlockSize($keyId);
        $output = '';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            openssl_private_encrypt($input, $out, $keyId);
            $output .= $out;
        }
        openssl_free_key($keyId);
        //$output = bin2hex($output);
        return $output;
    }

    static function decryptByPub($data, $key = null)
    {
    	$key = self::formatKey(self::$publicKey, 1);
        $keyId = openssl_pkey_get_public($key);
        $maxlength = self::getMaxDecryptBlockSize($keyId);
        $output = '';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            openssl_public_decrypt($input, $out, $keyId);
            $output .= $out;
        }
        openssl_free_key($keyId);
        return $output;
    }


	/**
	 * 格式化私钥文件
	 */
	static function formatKey($key, $note = 0) {
			$notice = 'PRIVATE';
			if ($note) $notice = 'PUBLIC';
	        $fKey = "-----BEGIN $notice KEY-----\n";
	        $fKey .= chunk_split($key, 64, "\n");
	        $fKey .= "-----END $notice KEY-----\n";
	        return $fKey;
	}

	 /**
     * 加密是获取最大加密长度
     *根据key的内容获取最大加密lock的大小，兼容各种长度的rsa keysize（比如1024,2048）
     * 对于1024长度的RSA Key，返回值为117
     * @param $keyRes
     * @return float
     */
    public static function getMaxEncryptBlockSize($keyRes){
        $keyDetail = openssl_pkey_get_details($keyRes);
        $modulusSize = $keyDetail['bits'];
        return $modulusSize/8 - 11;
    }


    /**
     * 解密时获取最大解密block的大小
     * 根据key的内容获取最大解密block的大小，兼容各种长度的rsa keysize（比如1024,2048）
     * 对于1024长度的RSA Key，返回值为128
     * @param $keyRes
     * @return float
     */
    public static function getMaxDecryptBlockSize($keyRes){
        $keyDetail = openssl_pkey_get_details($keyRes);
        $modulusSize = $keyDetail['bits'];
        return $modulusSize/8;
    }
}

/* 显示当前的内部字符编码*/
echo "显示当前的内部字符编码";
echo mb_internal_encoding();
echo "<br/>";


$str = '72D23397A0225BEDA4E658ECEA98AD1ACTios526e745a6a9a4864ac0f2d2a80dfcc1e';
$signature = Rsa::sign($str);
var_dump(Rsa::verify($str, $signature), $signature, mb_detect_encoding($signature), 
	//json_encode($signature),  json_last_error(), 
	bin2hex($signature));

echo "<br/>",  "<br/>";

echo '公钥加密->私钥解密:每次加密后的数据不同';
echo "<br/>";
for ($i=0;$i < 5; $i++) {
    $en = Rsa::encrypt($str);
    echo "<br/>";
    var_dump($en);
    var_dump( Rsa::decrypt($en));
}



echo "<br/>",  "<br/>";
echo '私钥加密->公钥解密:每次加密后的数据相同';

echo "<br/>";

for ($i=0;$i < 5; $i++) {
    $en1 = Rsa::encryptByPri($str);
    echo "<br/>";
    var_dump($en1);
    var_dump(Rsa::decryptByPub($en1));
}








