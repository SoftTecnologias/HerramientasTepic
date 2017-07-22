<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nombre');
            $table->string('calle');
            $table->string('colonia');
            $table->string('numero_int');
            $table->string('numero_ext');
            $table->integer('cp');
            $table->string('estado');
            $table->string('municipio');
            $table->string('telefono1');
            $table->string('telefono2');
            $table->string('email');
            $table->string('contacto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('proveedores');
    }
}
