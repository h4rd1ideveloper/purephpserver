<?php

namespace App\database\seeds;
date_default_timezone_set("America/Sao_Paulo");

use App\lib\Helpers;
use App\model\User;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        Helpers::setupIlluminateConnectionAsGlobal();
        Helpers::forMany(
            fn() => (new User(Helpers::fakerUserToSeed()))->save(),
            500
        );
    }
}
