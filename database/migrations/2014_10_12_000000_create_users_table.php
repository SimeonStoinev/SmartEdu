<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->string('confirmToken', 32);
            $table->index('confirmToken');
            $table->timestamps();
        });

        DB::table('users')->insert( [
            'name' => 'test',
            'email' => 'test@abv.bg',
            'password' => '$2y$10$7zEW.0XWlpUpWb5uOOcPQeKRqivE/a5a3llhqOX8dQ9HFWD9rtWBi',
            'confirmToken' => ''
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
