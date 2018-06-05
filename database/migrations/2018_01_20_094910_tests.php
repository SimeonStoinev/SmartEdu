<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tests extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('questions');
            $table->longText('question_type');
            $table->longText('answers');
            $table->longText('right_answers');
            $table->string('points');
            $table->string('slug')->nullable();
            $table->longText('access_code')->nullable();
            $table->longText('finish_code')->nullable();
            $table->integer('grade')->nullable();
            $table->string('sub_class',1)->nullable();
            $table->string('eval_grid')->nullable();
            $table->longText('images')->nullable();
            $table->enum('status', ['active', 'closed', 'sketch']);
            $table->integer('students')->nullable();
            $table->integer('creator_id');
            $table->integer('folder_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
