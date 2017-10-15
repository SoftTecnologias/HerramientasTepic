<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicesDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_detail', function (Blueprint $table) {
            $table->integer('serviceid');
            $table->string('encargado',150);
            $table->string('horario',200);
            $table->string('precio_base');

            $table->foreign('serviceid')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('services_detail');
    }
}
