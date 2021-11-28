<?php

use App\data\model\User;
use App\infra\lib\Helpers;
use Phinx\Seed\AbstractSeed;

date_default_timezone_set("America/Sao_Paulo");


/**
 * Class UserSeeder
 */
class UserSeeder extends AbstractSeed
{

    public function run(): void
    {
  /*      Helpers::setupIlluminateConnectionAsGlobal();
        $faker = Factory::create('pt_BR');
        Helpers::forMany(
            fn() => (new User([
                'username' => $faker->userName,
                'password' => $faker->password(60, 60),
                'email' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address
            ]))->save(),
            500
        );*/
    }
}
