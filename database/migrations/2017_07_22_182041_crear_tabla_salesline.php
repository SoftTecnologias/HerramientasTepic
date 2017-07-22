<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaSalesline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesline', function (Blueprint $table) {
            $table->increments('saleslineid');
            $table->float('quantity');
            $table->float('saleprice');
            $table->float('subtotal');
            $table->integer('saleid');
            $table->integer('productid');

            $table->foreign('saleid')->references('saleid')->on('sale');
            $table->foreign('productid')->references('id')->on('product');
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
        Schema::drop('salesline');
    }
}
