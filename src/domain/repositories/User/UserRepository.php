<?php

namespace App\domain\repositories\User;


use App\data\model\User;
use App\infra\lib\Helpers;

class UserRepository extends Helpers implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public static function getAllUsers()
    {
        return User::all();
    }

    public static function getUserById(int $Id)
    {
        return User::findOrFail($Id);
    }

    /**
     * @inheritDoc
     */
    public static function deleteUser($Id): int
    {
        return User::destroy($Id);
    }

    public static function createUser(array $UserDetails): User
    {
        return User::create($UserDetails);
    }

    public static function updateUser(int $UserId, array $newDetails)
    {
        return User::whereId($UserId)
            ->update($newDetails);
    }

    /**
     * @param string $value
     * @param string $key
     * @return array
     */
    public static function findUserBy(string $value, string $key): array
    {
        return self::tryCatch(static function () use ($value, $key) {
            return User::where($key, $value)
                ->firstOrFail()
                ->attributesToArray();
        }, []);
    }

}