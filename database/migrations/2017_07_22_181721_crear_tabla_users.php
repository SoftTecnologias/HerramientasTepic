<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('lastname',130);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone',20)->unique();
            $table->dateTime('signindate');
            $table->longText('photo');
            $table->integer('userprice');
            $table->char('status',1);
            $table->string('apikey');
            $table->string('username',16)->unique();
            $table->integer('roleid');
            $table->foreign('roleid')->references('id')->on('roles');
            $table->string('stripe_carge_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
