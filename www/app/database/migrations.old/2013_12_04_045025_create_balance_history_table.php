<?php

use Illuminate\Database\Migrations\Migration;

class CreateBalanceHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('balance_history');

        Schema::create('balance_history', function($table)
        {
            $table->increments('id');
			$table->integer('uid')->unsigned();
			$table->string('currency', 5);
			$table->float('pre_amount');
			$table->float('amount');	
        	$table->string('receipt_id', 18);					
        	$table->string('memo', 255)->nullable();
			$table->timestamps();

			$table->foreign('uid')->references('uid')->on('users');
			$table->foreign('receipt_id')->references('id')->on('receipts');
        });		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('balance_history');
	}

}