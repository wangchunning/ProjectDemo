<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorActivityLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('administrator_activity_log');
		
        Schema::create('administrator_activity_log', function($table)
        {
        	$table->increments('id');
			$table->integer('uid')->unsigned();
			$table->string('user_name', 16)->default('');
			$table->tinyInteger('op_category')->unsigned()->default(0);
			$table->tinyInteger('op_type')->unsigned()->default(0);
			$table->tinyInteger('operation')->unsigned()->default(0);
			$table->integer('obj_id')->unsigned()->default(0);
			$table->text('desc');
			$table->timestamps();

			$table->index('uid');
			$table->index('user_name');
			$table->index('obj_id');
			$table->index('created_at');
			
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
		Schema::drop('administrator_activity_log');
	}

}
