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
		// Creates the permission_role (Many-to-Many relation) table
        Schema::create('permission_role', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions'); // assumes a users table
            $table->foreign('role_id')->references('id')->on('roles');
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