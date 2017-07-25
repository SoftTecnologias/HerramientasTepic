<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaLocalidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localidades', function (Blueprint $table) {
            $table->increments('id_localidad');
            $table->string('name',45);
            $table->integer('municipio_id')->nullable();
            $table->foreign('municipio_id')->references('id_municipio')->on('municipios');
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
        Schema::drop('localidades');
    }
}
