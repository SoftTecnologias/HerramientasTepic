<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('idcart');
            $table->float('qty');

            $table->integer('productid');
            $table->integer('userid');

            $table->foreign('productid')->references('id')->on('product');
            $table->foreign('userid')->references('id')->on('users');

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
        Schema::drop('cart');
    }
}
