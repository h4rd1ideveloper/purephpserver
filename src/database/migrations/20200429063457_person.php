<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

class Person extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('users', ['id' => false, 'primary_key' => ['user_id']]);
        $users
            ->addColumn('user_id', 'binary', [
                'default' => Literal::from('UNHEX(REPLACE(UUID(), "-",""))'),
                'limit' => 16
            ])
            ->addColumn('username', 'string', ['limit' => 20])
            ->addColumn('password', 'string', ['limit' => 60])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('first_name', 'string', ['limit' => 30])
            ->addColumn('last_name', 'string', ['limit' => 30])
            ->addColumn('phone', 'string', ['limit' => strlen('(32) 9 98868-4312')])
            ->addColumn('phone_2', 'string', ['limit' => strlen('(32) 3462-3107'), 'null' => true])
            ->addColumn('address', 'string', ['limit' => 200, 'null' => true])
            ->addTimestampsWithTimezone()
            ->addIndex(['username', 'email'], ['unique' => true])
            ->create();
    }
}