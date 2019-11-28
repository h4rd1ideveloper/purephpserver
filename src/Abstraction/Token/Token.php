<?php


namespace App\Abstraction\Token;


use Firebase\JWT\JWT;

class Token extends JWT
{
    public static function decodePiece(string $headb64)
    {
        return self::jsonDecode(self::urlsafeB64Decode($headb64));
    }

    public static function isValidByKey(string $token, $key, string $alg = 'SHA256'): bool
    {
        $token = explode('.', $token);
        if (count($token) === 3) {
            [$headb64, $bodyb64, $cryptob64] = $token;
            $sig = self::urlsafeB64Decode($cryptob64);
            $hash = hash_hmac($alg, "$headb64.$bodyb64", $key, true);
            if (function_exists('hash_equals')) {
                return hash_equals($sig, $hash);
            }
            $len = min(self::safeStrlen($sig), static::safeStrlen($hash));
            $status = 0;
            for ($i = 0; $i < $len; $i++) {
                $status |= ord($sig[$i]) ^ ord($hash[$i]);
            }
            $status |= (static::safeStrlen($sig) ^ static::safeStrlen($hash));
            return ($status === 0);
        }
        return false;
    }

    /**
     * Get the number of bytes in cryptographic strings.
     *
     * @param string
     *
     * @return int
     */
    private static function safeStrlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }
}

$key = 'KEY';
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);
