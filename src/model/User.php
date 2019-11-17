<?php

namespace App\model;

use App\Abstraction\UserAbstraction;
use Exception;

/**
 * Class User
 */
class User extends UserAbstraction
{
    /**
     * @param array $credentials
     * @return User
     */
    public static function userFactory(array $credentials): User
    {
        try {
            return new User($credentials);
        } catch (Exception $e) {

        }
    }

    /**
     * @var Dao|null
     */
    private $DB = null;

    /**
     * User constructor.
     * @param array $credentials
     * @throws Exception
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
     * @param string|null $orderBy
     * @param string|null $limit
     * @return array|bool
     */
    public function listAll(?string $orderBy = null, ?string $limit = null): array
    {
        $this->DB->select(
            'users',
            '*',
            null,
            null,
            null,
            $orderBy,
            $limit
        );
        return $this->DB->getResult();
    }

    /**
     * @param UserAbstraction $user
     * @return array
     */
    public function createUser(UserAbstraction $user): array
    {
        if (!count($this->findUser($user))) {
            $this->DB->insert('users', $user->getDatabaseSchemaRegistration());
            return ['error' => false, 'message' => 'User created', 'raw' => $user->getDatabaseSchemaRegistration()];
        }
        return [
            'error' => true,
            'message' => 'There is already a user with the same credentials.',
            'raw' => ['exist' => $this->DB->getResult(), 'send' => $user->getDatabaseSchemaRegistration()]
        ];
    }

    /**
     * @param UserAbstraction $user
     * @return array
     */
    public function findUser(UserAbstraction $user)
    {
        $this->DB->select(
            'users',
            '*',
            null,
            $this->getDatabaseSchemaFinder()
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
     * @param string|int $id
     * @return array
     */
    public function findUserById($id)
    {
        $this->DB->select('users', '*', null, ['_id' => $id]);
        return $this->DB->getResult();
    }
}