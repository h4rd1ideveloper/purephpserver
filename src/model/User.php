<?php

namespace App\model;

use InvalidArgumentException;
use Lib\Bcrypt;
use Lib\Helpers;

/**
 * Class User
 */
class User extends Bcrypt
{
    /**
     * @var Dao|null
     */
    private $DB = null;

    /**
     * User constructor.
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->DB = new Dao(
            $credentials['host'],
            $credentials['user'],
            $credentials['pass'],
            $credentials['name']
        );
        $this->DB->connect();
    }

    /**
     * User constructor.
     * old session_start();
     * session_regenerate_id();
     * if(!isset($_SESSION['users']))
     * $_SESSION['users'] = array();
     */

    public function __destruct()
    {
        $this->DB->disconnect();
        unset($this->DB);
    }

    /**
     * Return all users in $_SESSION['users']
     * @param bool $orderBy
     * @param bool $limit
     * @return array|bool
     */
    public function listAll($orderBy = false, $limit = false): array
    {
        $this->DB->select(
            'users',
            '*',
            null,
            null,
            null,
            $orderBy ?: null,
            $limit ?: null
        );
        return $this->DB->getResult();
    }

    /**
     * @param array $params
     * @return array
     */
    public function createUser(array $params): array
    {
        if (isset($params['login'], $params['pass'], $params['meta'])) {
            $params['token'] = self::userToken($params['login'], $params['pass']);
            if (!count($this->findUser($params['login'], $params['pass']))) {
                $this->DB->insert('users', $params);
                return ['error' => false, 'message' => 'Usuario criado com sucesso', 'raw' => $params];
            }
            return [
                'error' => true,
                'message' => 'Ja existe um usuario cadastrado com estes dados',
                'raw' => ['existente' => $this->DB->getResult(), 'enviado' => $params]
            ];
        }
        return [
            'error' => true,
            'message' => 'Nome e senha, são obrigatorios para criação de um novo usuario',
            'raw' => $params
        ];
    }

    /**
     * @param string $login
     * @param string $uncrypPass
     * @return string
     */
    public static function userToken(string $login, string $uncrypPass)
    {
        if (!Helpers::stringIsOk($login) || !Helpers::stringIsOk($uncrypPass)) {
            throw new InvalidArgumentException('login and pass must be a valid string');
        }
        return self::hash($login . $uncrypPass);
    }

    /**
     * @param string $login
     * @param string $pass
     * @return array
     */
    public function findUser(string $login, string $pass)
    {
        $this->DB->select(
            'users',
            '*',
            null,
            [
                'login' => $login,
                'pass' => $pass
            ]
        );
        return $this->DB->getResult();
    }

    /**
     * @param integer|string $id
     * @return array
     */
    public function deleteUser($id)
    {
        if (count($this->findUserById($id))) {
            $success = $this->DB->delete('users', ['_id' => $id]);
            return $success ?
                ['error' => !$success, 'message' => "Usuario de id: $id, deletado"] :
                ['error' => $success, 'message' => 'something is wrong'];
        }
        return ['error' => true, 'message' => 'Não foi possivel localizar um usuario com este id', 'raw' => $id];
    }

    /**
     * @param $id
     * @return array
     */
    public function findUserById($id)
    {
        $this->DB->select('users', '*', null, ['_id' => $id]);
        return $this->DB->getResult();
    }
}