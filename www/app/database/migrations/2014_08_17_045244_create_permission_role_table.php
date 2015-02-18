<?php

use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('permission_role');

        Schema::create('permission_role', function($table)
        {

            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->primary(array('permission_id', 'role_id'));
            
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
        Schema::drop('permission_role');
    }

}