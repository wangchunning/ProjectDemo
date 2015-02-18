<?php

use Illuminate\Database\Migrations\Migration;

class CreateRecieptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('receipts');

        Schema::create('receipts', function($table)
        {

        	$table->string('id', 18);
			$table->integer('uid')->unsigned();
			$table->enum('type', array('All', 'Deposit', 'FX Wish', 'FX Deal', 'Withdraw'));
			$table->enum('status', array('Waiting for fund', 'Processing', 'Completed', 'Voided', 'Canceled'));
			$table->text('data');
			$table->timestamps();

			$table->primary('id');
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
		Schema::drop('receipts');
	}

}