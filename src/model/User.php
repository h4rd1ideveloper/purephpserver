<?php

namespace App\model;

use App\assets\lib\Dao;
use App\assets\lib\Helpers;

/**
 * Class User
 */
class User extends Dao
{
    /**
     * User constructor.
     * old session_start();
     * session_regenerate_id();
     * if(!isset($_SESSION['users']))
     * $_SESSION['users'] = array();
     */

    /**
     * Return all users in $_SESSION['users']
     * @param bool $orderBy
     * @return mixed
     */
    public function listAll($orderBy = false)
    {
        parent::select('', '*', null, null, ($orderBy === false || !Helpers::stringIsOk($orderBy)) ? '' : $orderBy);
        return parent::getResult();
    }

    /**
     * @param array $params
     * @return array
     */
    public function createUser($params)
    {
        if (isset($params['name']) && isset($params['passwd']) && isset($params['data'])) {
            parent::select('users', '*', '', array('name' => $params['name'], 'passwd' => $params['passwd']));
            if (!parent::getNumResults()) {
                parent::insert('users', $params);
                return array('error' => false, 'message' => 'Usuario criado com sucesso', 'raw' => $params);
            }
            return array('error' => true, 'message' => 'Ja existe um usuario cadastrado com estes dados', 'raw' => array('existente' => parent::getResult(), 'enviado' => $params));
        }
        return array('error' => true, 'message' => 'Nome e senha, são obrigatorios para criação de um novo usuario', 'raw' => $params);
    }

    /**
     * @param integer|string $id
     * @return array
     */
    public function deleteUser($id)
    {
        if (!Helpers::stringIsOk($id)) {
            return array('error' => true, 'message' => 'Identificação invalida', 'raw' => $id);
        }
        parent::select('users', '*', null, array('_id' => $id));
        if (parent::getNumResults()) {
            parent::delete('users', array('_id' => $id));
            return array('error' => false, 'message' => 'Usuario deletado');
        }
        return array('error' => true, 'message' => 'Não foi possivel localizar um usuario com este id', 'raw' => $id);
    }
}