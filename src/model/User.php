<?php

namespace App\model;

use App\Abstraction\UserAbstraction;
use Cake\Database\Query;
use Cake\Datasource\ConnectionManager;

/**
 * Class User
 */
class User
{
    /**
     * @var Query|null
     */
    private $DB = null;

    /**
     * User constructor.
     */
    public function __construct()
    {
        ConnectionManager::setConfig('default', ['url' => 'mysql://root:@localhost/app_dev']);
        $this->DB = ConnectionManager::get('default')->newQuery();
    }


    public function __destruct()
    {
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
        $q = $this->DB
            ->select('*')
            ->from('users');
        if ($orderBy) {
            $q->order($orderBy);
        }
        if ($limit) {
            $q->limit($limit);
        }
        return $q->execute()
            ->fetchAll();
    }

    /**
     * @param UserAbstraction $user
     * @return array
     */
    public function createUser(UserAbstraction $user): array
    {
        if ($this->existUser($user)) {
            return [
                'error' => true,
                'message' => 'There is already a user with the same credentials.',
                'raw' => $this->findUser($user)
            ];
        }
        $this->DB->insert($user->getDatabaseSchemaRegistration())->into('users')->execute();
        return ['error' => false, 'message' => 'User created', 'raw' => $user->getDatabaseSchemaRegistration()];
    }

    public function existUser(UserAbstraction $user): bool
    {
        return (bool)
            $this->DB
                ->select('id')
                ->from('users')
                ->where($user->schemaFinder())
                ->limit(1)
                ->execute()
                ->count() > 0;
    }

    /**
     * @param UserAbstraction $user
     * @return array
     */
    public function findUser(UserAbstraction $user): array
    {
        return $this->DB
            ->select('*')
            ->from('users')
            ->where($user->schemaFinder())
            ->execute()
            ->fetchAll();
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