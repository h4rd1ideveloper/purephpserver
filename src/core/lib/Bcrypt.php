<?php

namespace Lib;
/**
 * Crypt hashing class
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @link   https://gist.github.com/3438461
 */
class Bcrypt
{
    /**
     * Default salt prefix
     *
     * @see http://www.php.net/security/crypt_blowfish.php
     *
     * @var string
     */
    const _saltPrefix = '2a';
    /**
     * Default hashing cost (4-31)
     *
     * @var integer
     */
    const _defaultCost = 8;
    /**
     * Salt limit length
     *
     * @var integer
     */
    const _saltLength = 22;

    /**
     * Hash a string
     *
     * @param string $string The string
     * @param integer $cost The hashing cost
     *
     * @return string
     * @see    http://www.php.net/manual/en/function.crypt.php
     *
     */
    public static function hash(string $string, ?int $cost = null): string
    {
        if ($cost === null) {
            $cost = self::_defaultCost;
        }
        return crypt($string, self::__generateHashString($cost, self::generateRandomSalt()));
    }

    /**
     * Build a hash string for crypt()
     *
     * @param integer $cost The hashing cost
     * @param string $salt The salt
     *
     * @return string
     */
    private static function __generateHashString(int $cost, string $salt): string
    {
        return sprintf('$%s$%02d$%s$', self::_saltPrefix, $cost, $salt);
    }

    /**
     * Generate a random base64 encoded salt
     *
     * @return string
     */
    public static function generateRandomSalt(): string
    {
        // Salt seed
        $seed = uniqid(mt_rand(), true);
        // Generate salt
        $salt = base64_encode($seed);
        $salt = str_replace('+', '.', $salt);
        return substr($salt, 0, self::_saltLength);
    }

    /**
     * Check a hashed string
     *
     * @param string $string The string
     * @param string $hash The hash
     *
     * @return boolean
     */
    public static function check($string, $hash): bool
    {
        return (crypt($string, $hash) === $hash);
    }
}