<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{

    /**
     *
     *
     * @throws Exception
     */
    public function run()
    {
        /**
         *
         * $options = [
         * 'cost' => 11,
         * 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
         * ];
         * echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options);
         *
         * */

        $faker = Faker\Factory::create('pt_BR');
        $data = [];
        for ($i = 0; $i < 500; $i++) {
            $data[] = [
                'serial_number' => uniqid(random_bytes(10)),
                'level' => $i % 2 === 0 ? 'client' : 'seller',
                'name' => $faker->name,
                'email' => $faker->email,
                'username' => $faker->userName,
                'phone' => $faker->phoneNumber,
                'street' => $faker->streetAddress,
                'number' => $faker->randomNumber(3),
                'neighborhood' => $faker->secondaryAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => $faker->postcode
            ];
        }
        $this->table('users')->insert($data)->saveData();
    }

    /**
     * Execute before
     *
     * public function getDependencies()
     * {
     * return [
     * 'UserSeeder',
     * 'ShopItemSeeder'
     * ];
     * }
     */
}
