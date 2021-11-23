<?php

namespace App\domain\useCase;

use App\infra\servicies\security\Logger;
use App\main\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Authentication
{
    public static function login(string $login, string $password): array
    {
        try {
            $user = Factory::userRepository()::findUser($login);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
            Logger::debugLog("login: $login,try authenticate with wrong password", 'login|notAuthenticate');
        } catch (ModelNotFoundException $notFoundException) {
            Logger::debugLog($notFoundException->getMessage(), get_class($notFoundException));
        }
        return [];
    }
}