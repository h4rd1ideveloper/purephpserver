<?php


namespace App\lib;


use Exception;

/**
 * Bcrypt class
 *
 * @author Christian Metz
 * @since 23.06.2012
 * @copyright Christian Metz - MetzWeb Networks 2012
 * @version 1.0
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Bcrypt
{

    /**
     * Work cost factor
     * range between [04; 31]
     *
     * @var string
     */
    private static $_workFactor = 12;

    /**
     * Default identifier
     *
     * @var string
     */
    private static string $_identifier = '2y';

    /**
     * All valid hash identifiers
     *
     * @var array
     */
    private static array $_validIdentifiers = ['2a', '2x', '2y'];

    /**
     * Hash password
     *
     * @param string $password
     * @param int $workFactor
     * @return string
     * @throws Exception
     */
    public static function hashPassword($password, $workFactor = 0)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {
            throw new Exception('Bcrypt requires PHP 5.3 or above');
        }

        $salt = self::_genSalt($workFactor);
        return crypt($password, $salt);
    }

    /**
     * Generates the salt string
     *
     * @param integer $workFactor
     * @return string
     * @throws Exception
     */
    private static function _genSalt($workFactor)
    {
        if ($workFactor < 4 || $workFactor > 31) {
            $workFactor = self::$_workFactor;
        }

        $input = self::_getRandomBytes();
        $salt = '$' . self::$_identifier . '$';

        $salt .= str_pad($workFactor, 2, '0', STR_PAD_LEFT);
        $salt .= '$';

        $salt .= substr(strtr(base64_encode($input), '+', '.'), 0, 22);

        return $salt;
    }

    /**
     * Open SSL random generator
     *
     * @return string
     * @throws Exception
     */
    private static function _getRandomBytes()
    {
        if (!function_exists('openssl_random_pseudo_bytes')) {
            throw new Exception('Unsupported hash format.');
        }
        return openssl_random_pseudo_bytes(16);
    }

    /**
     * Check Bcrypt password
     *
     * @param string $password
     * @param string $storedHash
     * @return boolean
     * @throws Exception
     */
    public static function checkPassword($password, $storedHash)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {
            throw new Exception('Bcrypt requires PHP 5.3 or above');
        }

        self::_validateIdentifier($storedHash);
        $checkHash = crypt($password, $storedHash);

        return ($checkHash === $storedHash);
    }

    /**
     * Validate identifier
     *
     * @param string $hash
     * @return void
     * @throws Exception
     */
    private static function _validateIdentifier($hash)
    {
        if (!in_array(substr($hash, 1, 2), self::$_validIdentifiers)) {
            throw new Exception('Unsupported hash format.');
        }
    }

}