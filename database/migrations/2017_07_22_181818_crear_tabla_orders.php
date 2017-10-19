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
            $table->increments('id');
            $table->char('status',1);
            $table->integer('finished')->default(0); //Exclusivamente si el usuario termino o no el proceso
            $table->integer('userA')->nullable();
            $table->integer('userid');
            $table->foreign('userid')->references('id')->on('users');
            $table->integer('step')->defautl(1);
            $table->integer('delivery_type');
            $table->float('delivery_cost')->default(0);
            $table->float('subtotal')->default(0);
            $table->float('taxes')->default(0);
            $table->float('total')->default(0);
            $table->string('stripe_charge_id')->nullable();
            $table->timestamps();
            /* * delivery type
             * 1.- Recoger en sucursal (pago previo)
             * 2.- Recoger y pago en sucursal
             * 3.- Entrega en local
             * 4.- Envio exterior
             * */
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
