<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanerPrincipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_principal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo',100);
            $table->string('contenido',120);
            $table->longText('img');
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
        Schema::drop('banner_principal');
    }
}
