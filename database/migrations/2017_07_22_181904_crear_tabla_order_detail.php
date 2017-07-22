<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('detailid');
            $table->integer('lineid')->nullable();
            $table->integer('qty')->nullable();
            $table->float('price')->nullable();
            $table->integer('productid');
            $table->integer('orderid')->nullable();

            $table->foreign('productid')->references('id')->on('product');
            $table->foreign('orderid')->references('orderid')->on('orders');
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
        Schema::drop('order_detail');
    }
}
