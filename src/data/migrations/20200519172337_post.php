<?php

use App\data\migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Post extends Migration
{
    public function change(): void
    {
        $this->schema->create('posts', static function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }
}

