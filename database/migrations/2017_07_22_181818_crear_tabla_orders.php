<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('orderid');
            $table->dateTime('orderdate');
            $table->char('status',1);
            $table->integer('userA');
            $table->float('total');
            $table->float('subtotal');
            $table->float('taxes');
            $table->integer('userid');
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
        Schema::drop('orders');
    }
}
