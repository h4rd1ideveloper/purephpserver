<?php

use App\Abstraction\UserAbstraction;
use App\model\User;
use Faker\Factory;
use Phinx\Seed\AbstractSeed;

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
        $faker = Factory::create('pt_BR');
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $absUser = new UserAbstraction();
            $absUser->setUsername($faker->userName)
                ->setPassword($faker->password)
                ->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName);
            (new User())->createUser($absUser);
        }
        $this->insert('users', $data);
        //$this->table('users')->truncate();
    }
}
