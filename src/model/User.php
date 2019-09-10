<?php
namespace App\model;
/**
 * Class User
 */
class User {
    /**
     * User constructor.
     */
    public function __construct() {
        session_start();
        session_regenerate_id();
        if(!isset($_SESSION['users']))
            $_SESSION['users'] = array();
    }

    /**
     * Return all users in $_SESSION['users']
     * @return mixed
     */
    public function listAll() {
        return $_SESSION['users'];
    }

    /**
     * @param string $name
     */
    public function createNew($name) {
        $_SESSION['users'][] = $name;
    }

    /**
     *
     */
    public function deleteAll() {
        unset($_SESSION['users']);
        session_destroy();
    }
}