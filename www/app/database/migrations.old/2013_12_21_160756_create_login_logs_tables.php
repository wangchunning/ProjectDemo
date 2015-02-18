<?php

use Illuminate\Database\Migrations\Migration;

class CreateLoginLogsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('login_logs', function($table)
        {
			$table->string('email');
			$table->string('ip', 128);
			$table->timestamps();

			$table->index('email');
			$table->index('ip');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('login_logs');
	}

}