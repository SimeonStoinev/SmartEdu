<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Votes extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('user_id');
            $table->integer('poll_id');
            $table->integer('a0')->default(0);
            $table->integer('a1')->default(0);
            $table->integer('a2')->default(0);
            $table->integer('a3')->default(0);
            $table->integer('a4')->default(0);
            $table->integer('a5')->default(0);
            $table->integer('a6')->default(0);
            $table->integer('a7')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
