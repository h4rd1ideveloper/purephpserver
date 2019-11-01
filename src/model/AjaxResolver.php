<?php


namespace App\model;


use App\assets\lib\Dao;


/**
 * Class AjaxResolver
 * @package App\model
 */
class AjaxResolver
{
    /**
     * @var array|bool
     */
    public static $credentials;

    /**
     * AppController constructor.
     * @param bool|array $flag
     */
    public function __construct($flag = false)
    {
        if ($flag) {
            self::$credentials = [
                'host' => DB_HOST,
                'user' => DB_USER,
                'pass' => DB_PASS,
                'name' => DB_NAME
            ];
        } elseif (is_array($flag)) {
            self::$credentials = $flag;
        }
    }

    /**
     * @return array|bool|object
     */
    public static function getAllUsers()
    {
        $user = new User(self::$credentials);
        return $user->listAll();
    }

    /**
     * @param array $credentials
     * @param string $table
     * @param string $fields
     * @param string $where
     * @param string $limit
     * @return array
     */
    public static function autocomplete(array $credentials, string $table, string $fields, string $where, string $limit): array
    {
        $DB = new Dao(
            $credentials['host'],
            $credentials['user'],
            $credentials['pass'],
            $credentials['name']
        );
        if ($DB->connect()) {
            $DB->select(
                $table, $fields, null, $where, null, null, $limit
            );
            return $DB->getResult();
        }
        return ['error' => true, 'message' => 'Fail to connect DB'];
    }

    public static function CreateNewUser(string $login, string $pass, string $name, string $meta)
    {
        return (new User(self::$credentials))->createUser(['login' => $login, 'pass' => $pass, 'name' => $name, 'meta' => $meta]);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getUserById($id)
    {
        $user = new User(self::$credentials);
        return $user->findUserById($id);
    }

    /**
     * @param $login
     * @param $pass
     * @return array
     */
    public static function getUserByToken($login, $pass)
    {
        $user = new User(self::$credentials);
        return $user->findUser($user::userToken($login, $pass));
    }
}
