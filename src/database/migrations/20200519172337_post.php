<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

class Post extends AbstractMigration
{
    public function change()
    {
        $users = $this->table('posts', ['id' => false, 'primary_key' => ['post_id']]);
        $users
            ->addColumn(
                'post_id',
                'binary',
                [
                    'default' => Literal::from('UNHEX(REPLACE(UUID(), "-",""))'),
                    'limit' => 16
                ]
            )
            ->addColumn('content', 'string')
            ->addColumn('_user_id', 'binary')
            ->addForeignKey('_user_id', 'users', 'user_id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addTimestampsWithTimezone()
            ->create();
    }
}

