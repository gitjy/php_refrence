<?php

/**
*  * @link     https://github.com/firebase/php-jwt
*/

class Jwt
{
	static $leeway = 0;
	
    public static $supported_algs = array(
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'RS256' => array('openssl', 'SHA256'),
        'RS384' => array('openssl', 'SHA384'),
        'RS512' => array('openssl', 'SHA512'),
    );

	static function decode($jwt, $simple = false)
	{
		//解析
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new Exception('Wrong number of segments');
        }
        list($headerEncode, $bodyEncode, $signEncode) = $tks;
        if (null === ($header = static::jsonDecode(static::urlsafeB64Decode($headerEncode)))) {
            throw new Exception('Invalid header encoding');
        }
        if (null === $payload = static::jsonDecode(static::urlsafeB64Decode($bodyEncode))) {
            throw new Exception('Invalid claims encoding');
        }
        if (false === ($sig = static::urlsafeB64Decode($signEncode))) {
            throw new Exception('Invalid signature encoding');
        }
        if (empty($header['alg'])) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        $decode =[
        	'header' => $header,
        	'payload' => $payload,
        	'sign' => $sig,
        	'headerEncode' => $headerEncode,
        	'bodyEncode' => $bodyEncode,
        	'signEncode' => $signEncode,
        ];
        if ($simple) {
            $decode = array_slice($decode, 0 ,2);
        }
        return $decode;
	}

	/**
	 * 验证jwk
	 * 使用服务器的公钥验证JWS E256签名
	 * 验证时间早于exp令牌的值
	 * @param string|array        $jwt            The JWT
     * @param string  $key            The key, or map of keys.
	*/
	static function verifyJwt($jwt, $key, $allowed_algs = [])
	{
		if (is_string($jwt)) {
			$jwt = self::decode($jwt);
		}
		$header = $jwt['header'];
		$payload = $jwt['payload'];
		$sign = $jwt['sign'];
		$headerEncode = $jwt['headerEncode'];
		$bodyEncode = $jwt['bodyEncode'];
        if (!in_array($header['alg'], $allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }

        $timestamp = time();
        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (isset($payload['iat']) && $payload['iat'] > ($timestamp + static::$leeway)) {
            throw new Exception(
                'Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->iat)
            );
        }

        // Check if this token has expired.
        if (isset($payload['exp']) && ($timestamp - static::$leeway) >= $payload['exp']) {
        //    throw new Exception('Expired token');
        }

        //验签名
        if (empty($key)) {
            throw new Exception('Key may not be empty');
        }

        // Check the signature
        if (!static::verify($headerEncode . '.' . $bodyEncode, $sign, $key, $header['alg'])) {
            throw new Exception('Signature verification failed');
        }

        return $payload;
	}


    /**
     * Sign a string with a given key and algorithm.
     *
     * @param string            $msg    The message to sign
     * @param string|resource   $key    The secret key
     * @param string            $alg    The signing algorithm.
     *                                  Supported algorithms are 'HS256', 'HS384', 'HS512' and 'RS256'
     *
     * @return string An encrypted message
     *
     * @throws DomainException Unsupported algorithm was specified
     */
    public static function sign($msg, $key, $alg = 'HS256')
    {
        if (empty(static::$supported_algs[$alg])) {
            throw new DomainException('Algorithm not supported');
        }
        list($function, $algorithm) = static::$supported_algs[$alg];
        switch($function) {
            case 'hash_hmac':
                return hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm);
                if (!$success) {
                    throw new Exception("OpenSSL unable to sign data");
                } else {
                    return $signature;
                }
        }
    }

    /**
     * Verify a signature with the message, key and method. Not all methods
     * are symmetric, so we must have a separate verify and sign method.
     *
     * @param string            $msg        The original message (header and body)
     * @param string            $signature  The original signature
     * @param string|resource   $key        For HS*, a string key works. for RS*, must be a resource of an openssl public key
     * @param string            $alg        The algorithm
     *
     * @return bool
     *
     * @throws DomainException Invalid Algorithm or OpenSSL failure
     */
    public static function verify($msg, $signature, $key, $alg)
    {
    	if (empty(static::$supported_algs[$alg])) {
            throw new Exception('Algorithm not supported');
        }

        list($function, $algorithm) = static::$supported_algs[$alg];
        switch($function) {
            case 'openssl':
                $success = openssl_verify($msg, $signature, $key, $algorithm);
                if ($success === 1) {
                    return true;
                } elseif ($success === 0) {
                    return false;
                }
                // returns 1 on success, 0 on failure, -1 on error.
                throw new DomainException(
                    'OpenSSL error: ' . openssl_error_string()
                );
            case 'hash_hmac':
            default:
                $hash = hash_hmac($algorithm, $msg, $key, true);
                if (function_exists('hash_equals')) {
                    return hash_equals($signature, $hash);
                }
                $len = min(static::safeStrlen($signature), static::safeStrlen($hash));

                $status = 0;
                for ($i = 0; $i < $len; $i++) {
                    $status |= (ord($signature[$i]) ^ ord($hash[$i]));
                }
                $status |= (static::safeStrlen($signature) ^ static::safeStrlen($hash));

                return ($status === 0);
        }
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function jsonDecode($input) {
    	$data = json_decode($input, true);
    	return $data;
    }

    /**
     * Parse a JWK key
     * @param $source
     * @return resource|array an associative array represents the key
     */
    public static function parseKey($source)
    {
        if (!empty($source) && isset($source['kty']) && isset($source['n']) && isset($source['e'])) {
            switch ($source['kty']) {
                case 'RSA':
                    if (array_key_exists('d', $source))
                        throw new Exception('Failed to parse JWK: RSA private key is not supported');

                    $pem = self::createPemFromModulusAndExponent($source['n'], $source['e']);
                    $pubKey = openssl_pkey_get_public($pem);
                    if ($pubKey !== false)
                        return $pubKey;
                    break;
                default:
                    //Currently only RSA is supported
                    break;
            }
        }

        throw new Exception('Failed to parse JWK');
    }

    /**
     *
     * Create a public key represented in PEM format from RSA modulus and exponent information
     *
     * @param string $n the RSA modulus encoded in Base64
     * @param string $e the RSA exponent encoded in Base64
     * @return string the RSA public key represented in PEM format
     */
    private static function createPemFromModulusAndExponent($n, $e)
    {
        $modulus = self::urlsafeB64Decode($n);
        $publicExponent = self::urlsafeB64Decode($e);


        $components = array(
            'modulus' => pack('Ca*a*', 2, self::encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack('Ca*a*', 2, self::encodeLength(strlen($publicExponent)), $publicExponent)
        );

        $RSAPublicKey = pack(
            'Ca*a*a*',
            48,
            self::encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
        );


        // sequence(oid(1.2.840.113549.1.1.1), null)) = rsaEncryption.
        $rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
        $RSAPublicKey = chr(0) . $RSAPublicKey;
        $RSAPublicKey = chr(3) . self::encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;

        $RSAPublicKey = pack(
            'Ca*a*',
            48,
            self::encodeLength(strlen($rsaOID . $RSAPublicKey)),
            $rsaOID . $RSAPublicKey
        );

        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
            chunk_split(base64_encode($RSAPublicKey), 64) .
            '-----END PUBLIC KEY-----';

        return $RSAPublicKey;
    }

    /**
     * DER-encode the length
     *
     * DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4.  See
     * {@link http://itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf#p=13 X.690 paragraph 8.1.3} for more information.
     *
     * @access private
     * @param int $length
     * @return string
     */
    private static function encodeLength($length)
    {
        if ($length <= 0x7F) {
            return chr($length);
        }

        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }

}


class AppleJwt extends Jwt
{

    /**
     * 获取苹果公钥
     * Fetch Apple's public key from the auth/keys REST API to use to decode
     * the Sign In JWT.
     *
     * @param string $publicKeyKid
     * @return array
     */
    public static function fetchPublicKey(string $publicKeyKid) : array {
        $content = file_get_contents('https://appleid.apple.com/auth/keys');
        $keys = json_decode($content, true);	//JWKSet.keys

        if(!isset($keys['keys']) || count($keys['keys']) < 1) {
            throw new Exception('Invalid key format.');
        }

        $keys = array_column($keys['keys'], null, 'kid');
        $key = $keys[$publicKeyKid];
        $parsedPublicKey= self::parseKey($key);
        $keyDetails = openssl_pkey_get_details($parsedPublicKey);

        if(!isset($keyDetails['key'])) {
            throw new Exception('Invalid public key details.');
        }
        return [
            'publicKey' => $keyDetails['key'],
            'alg' => $key['alg']
        ];
    }

    /**
    *验证该iss字段包含https://appleid.apple.com
	*验证该aud字段是开发人员的client_id
    */
    public static function verifyIdentityToken($jwt)
    {
    	$jwt = self::decode($jwt);
    	$KeyKid = $jwt['header']['kid'];
    	//身份令牌中的密钥标识符kid和公钥JWK里密钥标识符kid相同
    	$publicKeyData = self::fetchPublicKey($KeyKid);

        $key = $publicKeyData['publicKey'];
        $alg = $publicKeyData['alg'];
    	parent::verifyJwt($jwt, $key, [$alg]);
    	$payload = $jwt['payload'];


    	//验证该iss字段包含https://appleid.apple.com
    	if ('https://appleid.apple.com' != $payload['iss']) {
    		throw new Exception('iss verification failed');
    	}

    	return $payload;
    }
}


$identityToken = "eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmx0ZHkudGFsaSIsImV4cCI6MTU5Mzc3MDI1NywiaWF0IjoxNTkzNzY5NjU3LCJzdWIiOiIwMDA3MzcuMDhjMzBmNzk2MjczNGZmNWJhYzBlYjBmNmM5NDUyMzMuMDU1NiIsImNfaGFzaCI6ImJnOExEcHZfUWNtandHMklDVjU2VUEiLCJlbWFpbCI6ImE0MjQ3MjU2QDEyNi5jb20iLCJlbWFpbF92ZXJpZmllZCI6InRydWUiLCJhdXRoX3RpbWUiOjE1OTM3Njk2NTcsIm5vbmNlX3N1cHBvcnRlZCI6dHJ1ZX0.SZV5xBEvW8_4EQ6jd-IdZEGVHeukUmGNQ0q6SjYoQEpJcbxn0bUrWJtAmR11yM3oNyNCIPZf16N_j9WR09ijGCBDvRcuKCkp1r79Z-MMJnR38Tql0slrxknl9O7YTkNtKvVkrz1zMWFFJ57rIoEU2bv_fhWn0S-mnbYgBq6ZMqIE-tyeOVkF5oP2BRASY3qjY-FOm1T3m_uGAgsy6BSMClT6OuZqKERECImSVP2N0Bho0KXls4tTTBwDo4IkYWuhsR7dW9hQ7Wo7huXerHpcjHKHElrvGNFHVwhM80V-tHiio38H_fLhdm0aVkwWLNZ46rz-Ut9y8Cdk4Dgknr6kew";
$decode = AppleJwt::decode($identityToken, true);
//AppleJwt::verifyIdentityToken
var_dump($decode);