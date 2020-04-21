<?php

use App\database\migrations\Migration;

class UserTableCreate extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');//pk
            $table->bigInteger('serial_number');//id for future use
            $table->enum('level', ['client', 'seller'])->nullable();//level for future use
            $table->string('name');//Full name
            $table->string('email');//email
            $table->string('username', 10);//username for log in on system
            $table->string('phone', strlen('(32) 9 98868-4312'));//contact number with mask
            $table->string('phone_2', strlen('(32) 3462-3107'))->nullable();//optional extra contact number
            $table->string('street');//street address
            $table->string('number');//number address
            $table->string('neighborhood');//neighborhood address
            $table->string('city');//city address
            $table->string('state');//state address
            $table->string('zip');//zip-code address
            $table->ipAddress('visitor_at')->nullable();//optional ip address of visitor
            $table->macAddress('device_at')->nullable();//optional mac address of device visitor
            /**
             *json option for future use
             *@example  '["same-key-property":{"same-key":"same-value"},12,"same-string","same-bool":false]'
             **/
            $table->json('options')->nullable();//
            $table->timestamps();
            $table->unique(['serial_number', 'email', 'username']);
            $table->index(['email', 'username']);
        });
    }

    public function down()
    {
        $this->schema->drop('users');
    }
}
