<?php

date_default_timezone_set("America/Sao_Paulo");

use App\data\model\User;
use App\lib\Helpers;
use App\model\User;
use Faker\Factory;
use Phinx\Seed\AbstractSeed;

/**
 * Class UserSeeder
 */
class UserSeeder extends AbstractSeed
{

    public function run()
    {
        Helpers::setupIlluminateConnectionAsGlobal();
        $faker = Factory::create('pt_BR');
        Helpers::forMany(
            fn () => (new User([
                'username' => $faker->userName,
                'password' => $faker->password(60, 60),
                'email' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address
            ]))->save(),
            500
        );
    }
}
