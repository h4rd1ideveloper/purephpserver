<?php


namespace Lib;


/**
 * Crypt hashing class
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @link   https://gist.github.com/3438461
 */
class Crypt
{
    /**
     * @var string
     */
    protected static $_alg = 'sha512';

    /**
     * Check a hashed string
     *
     * @param string $string The string
     * @param string $hash The hash
     * @return boolean
     */
    public static function check(string $string, string $hash): bool
    {
        return (bool)(self::hash($string) === $hash);
    }

    /**
     * Hash a string
     *
     * @param string $string The string
     * @return string
     * @see    http://www.php.net/manual/en/function.crypt.php
     */
    public static function hash(string $string)
    {
        return hash($string, self::$_alg);
    }
}