<?php

namespace App\model;

use App\Abstraction\UserAbstraction;
use Cake\Database\Query;
use Cake\Datasource\ConnectionManager;
use Exception;

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
    {//'mysql://user:pass@localhost/database?'
        ConnectionManager::setConfig('default', ['url' => 'mysql://root:@localhost/app_dev']);
        $this->DB = ConnectionManager::get('default')->newQuery();
    }


    /**
     *
     */
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
            ->select('listAll.*', true)
            ->from('users as listAll', true);
        if ($orderBy) {
            $q->order("listAll.$orderBy", true);
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
        try {
            $insertQuery = $this->DB->into('users');
            $insertQuery->insert(array_keys($user->getDatabaseSchemaRegistration()));
            $insertQuery->clause('values');
            $insertQuery->values($user->getDatabaseSchemaRegistration());
            $insertQuery->execute();
            return [
                'error' => false,
                'message' => 'User created'
            ];
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => 'Mysql Error [User  created or not], sorry ' . $exception->getTraceAsString()
            ];
        }
    }

    /**
     * @param UserAbstraction $user
     * @return bool
     */
    public function existUser(UserAbstraction $user): bool
    {
        return (bool)
            $this->DB
                ->select('*', true)
                ->from('users', true)
                ->where($user->schemaFinder(), [], true)
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
            ->select('*', true)
            ->from('users', true)
            ->where($user->schemaFinder(), [], true)
            ->execute()
            ->fetch();

    }

    /**
     * @param UserAbstraction $user
     * @return array
     */
    public function deleteUser(UserAbstraction $user)
    {
        if ($id = $this->userId($user)) {
            $success = $this->DB->delete()->from('user as deleteUser', true)->where(['deleteUser.id' => $id['id']], [], true)->execute()->fetch();
            return $success ?
                ['error' => !$success, 'message' => "Usuario de id: $id, deletado"] :
                ['error' => $success, 'message' => 'something is wrong'];
        }
        return ['error' => true, 'message' => 'Não foi possivel localizar um usuario com este id', 'raw' => $id];
    }

    /**
     * @param UserAbstraction $user
     * @return int|false
     */
    public function userId(UserAbstraction $user)
    {
        return $this->DB
                ->select('id', true)
                ->from('users', true)
                ->where($user->schemaFinder(), [], true)
                ->limit(1)
                ->execute()
                ->fetch()[0]['id'] ?? false;
    }
}