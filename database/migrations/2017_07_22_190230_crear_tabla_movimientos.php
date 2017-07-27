<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaMovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proveedor_id')->unsigned();
            $table->float('total')->unsigned()->default(0);
            $table->integer('tipo')->default(1);
            $table->string('folio_factura');
            $table->string('observaciones');
            $table->boolean('status')->default(true);
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));

        });

        Schema::table('movimientos',function (Blueprint $table){
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });

        DB::statement('CREATE TRIGGER dbo.MOVIMIENTO_UPDATE_TRIGGER ON dbo.movimientos
                       FOR UPDATE 
                       AS
                       BEGIN
                            DECLARE @TIPO INT
                            DECLARE @MOVIMIENTO INT
                            DECLARE @CANTIDAD INT
                            DECLARE @PRODUCTO INT 
                            SELECT @MOVIMIENTO = id, @TIPO = tipo FROM deleted
                            DECLARE @ESTADO TINYINT
                        
                            SELECT @ESTADO = status FROM inserted
	                        IF @ESTADO = 0 
		                    BEGIN
		                        DECLARE DETALLES CURSOR FOR SELECT cantidad, producto_id FROM detalle_movimientos WHERE movimiento_id = @MOVIMIENTO
		                        OPEN DETALLES
		                        FETCH NEXT FROM DETALLES INTO @CANTIDAD, @PRODUCTO 
		                        WHILE @@FETCH_STATUS = 0
		                        BEGIN
			                        IF @TIPO = 1
				                        UPDATE product
				                        SET stock = stock - @CANTIDAD
				                        WHERE id = @PRODUCTO
			                        ELSE
				                        IF @TIPO = 2
					                        UPDATE product
					                        SET stock = stock + @CANTIDAD
					                        WHERE id = @PRODUCTO
			                        FETCH NEXT FROM DETALLES INTO @CANTIDAD, @PRODUCTO 
		                        END
		                        CLOSE DETALLES
		                        DEALLOCATE DETALLES
	                        END
                       END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimientos');
    }
}
