<?php

namespace App\main;

use App\domain\repositories\User\UserRepository;

class Factory
{
    public static function userRepository(): UserRepository
    {
        return new UserRepository();
    }
}