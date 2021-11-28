<?php

namespace App\domain\useCase;

use App\infra\lib\Helpers;
use App\main\Factory;

abstract class Authentication extends Helpers
{
    public static function login(string $login, string $password): bool
    {
        return self::tryCatch(static function () use ($password, $login) {
            $user = Factory::userRepository()::findUserBy($login, 'username');
            if (!count($user)) {
                $user = Factory::userRepository()::findUserBy($login, 'email');
            }
            return password_verify($password, $user['password']);
        }, false, true);
    }
}