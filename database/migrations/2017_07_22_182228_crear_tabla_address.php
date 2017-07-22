<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('addressid');
            $table->string('street',100);
            $table->string('streetnumbre',7)->nullable();
            $table->string('street2',100)->nullable();
            $table->string('street3',100)->nullable();
            $table->string('neigborhood',50)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('region',50)->nullable();
            $table->string('reference',200)->nullable();
            $table->integer('userid');
            $table->integer('city')->nullable();
            $table->integer('country')->nullable();
            $table->integer('state')->nullable();

            $table->foreign('city')->references('id_localidad')->on('localidades');
            $table->foreign('country')->references('id_municipio')->on('municipios');
            $table->foreign('state')->references('id_estado')->on('estados');
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
        Schema::drop('address');
    }
}
