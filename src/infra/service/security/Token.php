<?php

namespace App\infra\service\security;

use Firebase\JWT\JWT;

final class Token extends JWT
{
    public static function decodePiece(string $headBase64): object
    {
        return self::jsonDecode(self::urlsafeB64Decode($headBase64));
    }

    public static function isValidByKey(string $token_string, $key, string $alg = 'SHA256'): bool
    {
        $token = explode('.', $token_string);
        if (count($token) === 3) {
            [$headBase64, $bodyBase64, $cryptoBase64] = $token;
            $sig = self::urlsafeB64Decode($cryptoBase64);
            $hash = hash_hmac($alg, "$headBase64.$bodyBase64", $key, true);
            if (function_exists('hash_equals')) {
                return hash_equals($sig, $hash);
            }
            $len = min(self::safeStringLength($sig), self::safeStringLength($hash));
            $status = 0;
            for ($i = 0; $i < $len; $i++) {
                $status |= ord($sig[$i]) ^ ord($hash[$i]);
            }
            $status |= (self::safeStringLength($sig) ^ self::safeStringLength($hash));
            return ($status === 0);
        }
        return false;
    }

    private static function safeStringLength(string $str): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }
}