<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreacionTablaCostosenvio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CostoEnvio', function (Blueprint $table) {
            $table->integer('codigo_postal')->primary();
            $table->string('Nombre_Colonia');
            $table->float('costo_envio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('CostoEnvio');
    }
}
