<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activate_email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');


        });

        Schema::table('activate_email',function (Blueprint $table){
            DB::statement('CREATE TRIGGER activate_email_trigger ON activate_email
                           FOR DELETE
                           AS
                           BEGIN
                             DECLARE @USUARIO INT 
                             SELECT @USUARIO = user_id FROM deleted
  
                             UPDATE users 
                             SET status = \'A\'
                             WHERE id = @USUARIO 
                           END;'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activate_email');
    }
}
