<?php

namespace App\data\migrations;

use Illuminate\Database\Schema\Blueprint;

class Person extends Migration
{
    public function change(): void
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->uuid('uuid');
            $table->string('username', 20)->unique();
            $table->string('password', 60);
            $table->string('email', 20)->unique();
            $table->string('first_name', 20);
            $table->string('middle_name', 20)->nullable();
            $table->string('last_name', 20);
            $table->string('phone', strlen('(32) 3462-3107'))->nullable();
            $table->string('cellphone', strlen('(32) 9 98868-4312'))->nullable();
            $table->string('address', 60);
            $table->timestamps();
            $table->index(['id', 'uuid']);
        });
    }
}