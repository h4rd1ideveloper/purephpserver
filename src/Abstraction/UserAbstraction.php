<?php


namespace App\Abstraction;

use Lib\Helpers;

/**
 * Class UserAbstraction
 * @package App\Abstraction
 */
class UserAbstraction
{

    /**
     * @var string
     */
    private $first_name = null;

    /**
     * @var string
     */
    private $last_name = null;
    /**
     * @var string
     */
    private $username = null;
    /**
     * @var string
     */
    private $password = null;
    /**
     * @var string
     */
    private $_decrypt_pass = null;
    /**
     * @var string
     */
    private $email = null;
    /**
     * @var string
     */
    private $tel = null;

    /**
     * UserAbstraction constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(?string $username = null, ?string $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return UserAbstraction
     */
    public function setLastName(string $last_name): UserAbstraction
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function checkCryptPass(string $hash): bool
    {
        return password_verify($this->getDecryptPass(), $hash);
    }

    /**
     * @return string
     */
    public function getDecryptPass(): string
    {
        return $this->_decrypt_pass;
    }

    /**
     * @param string $_decrypt_pass
     * @return UserAbstraction
     */
    public function setDecryptPass(string $_decrypt_pass): UserAbstraction
    {
        $this->_decrypt_pass = $_decrypt_pass;
        return $this;
    }

    /**
     * @param string $decryptPass
     * @return bool
     */
    public function checkDecryptPass(string $decryptPass): bool
    {
        return password_verify($decryptPass, $this->getPassword());
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserAbstraction
     */
    public function setPassword(string $password): UserAbstraction
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return Helpers::toJson($this->_data());
    }

    private function _data()
    {
        return Helpers::Filter(
            get_object_vars($this), static function ($v): bool {
            return $v !== null;
        });
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserAbstraction
     */
    public function setEmail(string $email): UserAbstraction
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getTel(): string
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     * @return UserAbstraction
     */
    public function setTel(string $tel): UserAbstraction
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * @return array
     */
    public function getDatabaseSchemaRegistration(): array
    {
        return $this->_data();
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return UserAbstraction
     */
    public function setFirstName(string $first_name): UserAbstraction
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return array
     */
    public function schemaFinder()
    {
        return [
            'username' => self::getUsername()
        ];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserAbstraction
     */
    public function setUsername(string $username): UserAbstraction
    {
        $this->username = $username;
        return $this;
    }
}