<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestResults extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('personal_data');
            $table->integer('grade')->nullable();
            $table->string('sub_class',1)->nullable();
            $table->string('finish_code');
            $table->longText('shuffled_answers');
            $table->longText('shuffled_ra');
            $table->longText('closed_answers');
            $table->longText('open_answers');
            $table->string('student_link');
            $table->string('suggestions')->nullable();
            $table->boolean('verified');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->integer('test_id');
            $table->integer('points');
            $table->string('open_answers_points')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
}
