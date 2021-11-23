<?php

namespace App\domain\repositories\User;

use App\data\model\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllUsers();

    public static function getUserById(int $Id);

    /**
     * @param Collection|array|int|string $Id
     */
    public static function deleteUser($Id): int;

    public static function createUser(array $UserDetails): User;

    public static function updateUser(int $UserId, array $newDetails);
    public static function findUser(string $login);

}