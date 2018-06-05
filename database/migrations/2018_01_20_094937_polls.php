<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Polls extends Migration
{
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('question');
            $table->longText('answers');
            $table->string('slug')->nullable();
            $table->integer('creator_id');
            $table->integer('folder_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
