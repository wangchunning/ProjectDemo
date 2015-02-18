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
			$table->increments('id');
			$table->integer('role_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->timestamps();

			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			$table->foreign('user_id')->references('uid')->on('users')->onDelete('cascade');			
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