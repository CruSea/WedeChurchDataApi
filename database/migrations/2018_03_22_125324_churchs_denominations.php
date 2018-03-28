<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChurchsDenominations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('denominations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('churches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('church_name');
            $table->string('description');
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone_number');
            $table->string('email');
            $table->integer('denomination_id')->unsigned();
            $table->foreign('denomination_id')->references('id')->on('denominations')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
                
            $table->timestamps();
        });
        Schema::create('verify_churches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('churches')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('verified');
            $table->timestamps();
        });

        // Schema::create('church_user', function (Blueprint $table) {
        //     $table->integer('church_id')->unsigned();
        //     $table->integer('user_id')->unsigned();

        //     $table->foreign('user_id')->references('id')->on('users')
        //         ->onUpdate('cascade')->onDelete('cascade');
        //     $table->foreign('church_id')->references('id')->on('churches')
        //         ->onUpdate('cascade')->onDelete('cascade');

        //     $table->primary(['user_id', 'church_id']);
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('churches');
        Schema::drop('denominations');
    }
}
