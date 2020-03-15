<?php


use Phinx\Seed\AbstractSeed;
use Faker\Factory;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {

        $faker = Factory::create('pt_BR');;
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'username'      => $faker->userName,
                'password'      => password_hash($faker->password, PASSWORD_DEFAULT),
                'email'         => $faker->email,
                'full_name'     => substr("$faker->firstName $faker->lastName", 0, 30),
                'tel'           => $faker->mobileNumber
            ];
        }

        $this->table('users')->insert($data)->saveData();
    }
}
