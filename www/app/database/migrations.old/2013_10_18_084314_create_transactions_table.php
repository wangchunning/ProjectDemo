<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('transactions');
		
        Schema::create('transactions', function($table)
        {

        	$table->string('id', 15);
			$table->integer('uid')->unsigned();
			$table->enum('type', array('Deposit', 'FX Wish', 'FX Deal', 'Withdraw'));
			$table->enum('status', array('Waiting for fund', 'Completed', 'Voided', 'Canceled'));
			$table->float('amount');
			$table->string('currency', 5);
			$table->string('receipt_id', 18);
			$table->string('account_number', 120)->nullable();
			$table->text('data');
			$table->timestamps();
			
			$table->primary('id');
			$table->index('status');
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
		Schema::drop('transactions');
	}

}