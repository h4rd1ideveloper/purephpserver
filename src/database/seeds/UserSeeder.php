<?php

date_default_timezone_set("America/Sao_Paulo");

use App\lib\Helpers;
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
        $this
            ->table('users')
            ->insert(
                Helpers::many(
                    fn() => [
                        'username' => $faker->userName,
                        'password' => $faker->password(60, 60),
                        'email' => $faker->email,
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address
                    ],
                    500
                )
            )
            ->saveData();
    }
}
