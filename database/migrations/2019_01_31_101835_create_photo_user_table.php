<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoUserTable extends Migration
{

    public function up()
    {
        Schema::create('photo_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('photo_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('photo_id')
                ->references('id')
                ->on('photos');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('photo_user');
    }
}
