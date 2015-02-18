<?php

use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('role_user');

        Schema::create('role_user', function($table)
        {           

            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->primary(array('user_id', 'role_id'));
            
            $table->engine = 'InnoDB';
        });     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }

}