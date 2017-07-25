<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDetalleMovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movimiento_id')->unsigned();
            $table->integer('producto_id')->unsigned();
            $table->integer('cantidad');
            $table->float('importe');
            $table->float('total');
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('detalle_movimientos',function(Blueprint $table){
            $table->foreign('producto_id')->references('id')->on('product');
            $table->foreign('movimiento_id')->references('id')->on('movimientos')->onDelete('cascade');
        });

        DB::statement('CREATE TRIGGER dbo.DETALLE_INSERT_TRIGGER ON dbo.detalle_movimientos
                       FOR INSERT AS 
                       BEGIN 
                       --VARIABLES
                       DECLARE @TIPO TINYINT
                       DECLARE @CANTIDAD INT
                       DECLARE @PRODUCTO INT
                       DECLARE @MOVIMIENTO INT
                       DECLARE @TOTAL INT  
                       SELECT @TIPO = tipo FROM movimientos WHERE id = (SELECT TOP 1 movimiento_id FROM inserted)
                       SELECT @CANTIDAD = cantidad,  @PRODUCTO= producto_id, @TOTAL=total, @MOVIMIENTO = movimiento_id from inserted
                       IF @TIPO = 1 --ES UNA ENTRADA
	                       UPDATE product 
	                       SET stock = stock + @CANTIDAD
	                       WHERE id = @PRODUCTO
                       ELSE
                       IF @TIPO = 2 --ES UNA SALIDA
                           UPDATE product
                           SET stock = stock - @CANTIDAD
                           WHERE id = @PRODUCTO
                       UPDATE movimientos
                       SET total = total + @TOTAL
                       WHERE id = @MOVIMIENTO
                       END;');

        DB::statement('CREATE TRIGGER dbo.DETALLE_DELETE_TRIGGER ON dbo.detalle_movimientos
                       FOR INSERT
                       AS 
                       BEGIN 
                       --VARIABLES
                       DECLARE @TIPO TINYINT
                       DECLARE @CANTIDAD INT
                       DECLARE @PRODUCTO INT
                       DECLARE @MOVIMIENTO INT
                       DECLARE @TOTAL INT  
                       SELECT @TIPO = tipo FROM movimientos WHERE id = (SELECT TOP 1 movimiento_id FROM deleted)
                       SELECT @CANTIDAD = cantidad,  @PRODUCTO= producto_id, @TOTAL=total, @MOVIMIENTO = movimiento_id from deleted
                       IF @TIPO = 1 --ES UNA ENTRADA
	                       UPDATE product
	                       SET stock = stock - @CANTIDAD
	                       WHERE id = @PRODUCTO
                       ELSE
                       IF @TIPO = 2 --ES UNA SALIDA
                           UPDATE product
                           SET stock = stock + @CANTIDAD
                           WHERE id = @PRODUCTO
                       UPDATE movimientos
                       SET total = total - @TOTAL
                       WHERE id = @MOVIMIENTO
                       END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detalle_movimientos');
    }
}
