<?php


namespace App\Abstraction;


use Lib\Crypt;
use Lib\Helpers;

/**
 * Class UserAbstraction
 * @package App\Abstraction
 */
class UserAbstraction extends Crypt
{

    /**
     * @var string
     */
    private $id = null;
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var string
     */
    private $login = null;
    /**
     * @var string
     */
    private $pass = null;
    /**
     * @var string
     */
    private $decryptPass = null;
    /**
     * @var string
     */
    private $email = null;
    /**
     * @var string
     */
    private $tel = null;
    /**
     * @var string
     */
    private $phone = null;
    /**
     * @var string
     */
    private $meta = null;
    /**
     * @var string
     */
    private $date = null;

    /**
     * UserAbstraction constructor.
     * @param string $login
     * @param string $pass
     */
    public function __construct(?string $login, ?string $pass)
    {
        $this->login = $login;
        $this->pass = $pass;
    }


    /**
     * @param string $hash
     * @return bool
     */
    public function checkCryptPass(string $hash): bool
    {
        return self::check($this->getDecryptPass(), $hash);
    }

    /**
     * @return string
     */
    public function getDecryptPass(): string
    {
        return $this->decryptPass;
    }

    /**
     * @param string $decryptPass
     * @return UserAbstraction
     */
    public function setDecryptPass(string $decryptPass): UserAbstraction
    {
        $this->decryptPass = $decryptPass;
        return $this;
    }

    /**
     * @param string $decryptPass
     * @return bool
     */
    public function checkDecryptPass(string $decryptPass): bool
    {
        return self::check($decryptPass, $this->getPass());
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     * @return UserAbstraction
     */
    public function setPass(string $pass): UserAbstraction
    {
        $this->pass = self::hash($pass);
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
        return Helpers::toJson(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return UserAbstraction
     */
    public function setId(string $id): UserAbstraction
    {
        $this->id = $id;
        return $this;
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
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return UserAbstraction
     */
    public function setPhone(string $phone): UserAbstraction
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getMeta(): string
    {
        return $this->meta;
    }

    /**
     * @param string $meta
     * @return UserAbstraction
     */
    public function setMeta(string $meta): UserAbstraction
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return UserAbstraction
     */
    public function setDate(string $date): UserAbstraction
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return array
     */
    public function getDatabaseSchemaRegistration(): array
    {
        return [
            'login' => $this->getLogin(),
            'name' => $this->getName(),
            'pass' => Crypt::hash($this->getPass())

        ];
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return UserAbstraction
     */
    public function setLogin(string $login): UserAbstraction
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UserAbstraction
     */
    public function setName(string $name): UserAbstraction
    {
        $this->name = $name;
        return $this;
    }

    protected function getDatabaseSchemaFinder()
    {
        return [
            'login' => $this->getLogin(),
            'pass' => $this->getPass()
        ];
    }
}