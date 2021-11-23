<?php

namespace App\main;

use App\domain\repositories\User\UserRepository;

class Factory
{
    public static function userRepository(array $modelAttributes = []): UserRepository
    {
        return new UserRepository();
    }
}