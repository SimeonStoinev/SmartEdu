<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Charts extends Migration
{
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('results');
            $table->string('slug');
            $table->integer('creator_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charts');
    }
}
