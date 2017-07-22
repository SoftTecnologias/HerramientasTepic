<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',20);
            $table->string('name',200);
            $table->integer('stock');
            $table->string('currency',3);
            $table->longText('photo');
            $table->string('shortdescription',50);
            $table->string('longdescription',150);
            $table->integer('reorderpoint');
            $table->tinyInteger('quotation');
            $table->longText('photo2');
            $table->longText('photo3');
            $table->tinyInteger('selected');

            $table->integer('categoryid');
            $table->integer('brandid');
            $table->integer('subcategoryid');
            $table->integer('priceid');

            $table->foreign('categoryid')->references('id')->on('category');
            $table->foreign('subcategoryid')->references('id')->on('subcategory');
            $table->foreign('priceid')->references('id')->on('price');
            $table->foreign('brandid')->references('id')->on('brand');
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
        Schema::drop('product');
    }
}
