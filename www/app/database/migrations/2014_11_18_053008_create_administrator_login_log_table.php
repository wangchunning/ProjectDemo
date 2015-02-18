<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorLoginLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('administrator_login_log');
		
        Schema::create('administrator_login_log', function($table)
        {
        	$table->increments('id');
			$table->char('user_name', 16)->default('');
			$table->char('login_ip', 16)->default('');
			$table->timestamps();

			$table->index('user_name');
			
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
		Schema::drop('administrator_login_log');
	}

}
