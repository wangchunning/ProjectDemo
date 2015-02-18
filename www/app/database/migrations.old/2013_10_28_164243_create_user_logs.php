<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserLogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('user_logs');

        Schema::create('user_logs', function($table)
        {

        	$table->bigIncrements('id');
			$table->integer('uid')->unsigned();
			$table->enum('user_type', array('user', 'manager'))->default('user');
			$table->string('activity_type', 35);
			$table->string('action', 30);
			$table->integer('obj_id')->unsigned();
			$table->text('description');
			$table->string('ip_address', 64);
			$table->timestamps();

			$table->foreign('uid')->references('uid')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_logs');
	}

}