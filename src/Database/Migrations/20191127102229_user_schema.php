<?php

use Phinx\Migration\AbstractMigration;

class UserSchema extends AbstractMigration
{
    public function change()
    {
        $this->table('users')->exists() &&
        $this->table('users')->drop()->save();
    }

}
