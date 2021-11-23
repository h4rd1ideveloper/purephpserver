<?php

namespace App\domain\repositories\User;


use App\data\model\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
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
        return User::whereId($UserId)->update($newDetails);
    }

    /**
     * @param string $login
     * @return array
     */
    public static function findUser(string $login): array
    {
        try {
            $user = User::where('username', $login)->firstOrFail();
        } catch (ModelNotFoundException $notFoundException) {
            $user = User::where('email', $login)->firstOrFail();
        }
        return $user->attributesToArray();
    }
}