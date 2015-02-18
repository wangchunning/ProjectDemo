<?php

use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('billings');

		Schema::create('billings', function($table)
		{
			$table->string('id', 15);
			$table->integer('uid')->unsigned();
			$table->enum('status', array('Pending', 'Completed'));
			$table->timestamps();
			$table->softDeletes();

			$table->primary('id');
			$table->index('uid');
			$table->index('created_at');
			$table->foreign('uid')->references('uid')->on('users');

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
		//
		Schema::drop('billings');
	}

}
