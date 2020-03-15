<?php


namespace App;


use Firebase\JWT\JWT as JWT;

final class Token extends JWT
{
    public final static function decodePiece(string $headBase64)
    {
        return self::jsonDecode(self::urlsafeB64Decode($headBase64));
    }

    public final static function isValidByKey(string $token, $key, string $alg = 'SHA256'): bool
    {
        $token = explode('.', $token);
        if (count($token) === 3) {
            [$headBase64, $bodyBase64, $cryptoBase64] = $token;
            $sig = self::urlsafeB64Decode($cryptoBase64);
            $hash = hash_hmac($alg, "$headBase64.$bodyBase64", $key, true);
            if (function_exists('hash_equals')) {
                return hash_equals($sig, $hash);
            }
            $len = min(self::safeStringLength($sig), static::safeStringLength($hash));
            $status = 0;
            for ($i = 0; $i < $len; $i++) {
                $status |= ord($sig[$i]) ^ ord($hash[$i]);
            }
            $status |= (static::safeStringLength($sig) ^ static::safeStringLength($hash));
            return ($status === 0);
        }
        return false;
    }

    /**
     * Get the number of bytes in cryptographic strings.
     * length
     * @param string
     *
     * @return int
     */
    private final static function safeStringLength(string $str): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }
}