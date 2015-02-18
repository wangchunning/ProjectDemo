<?php

use Illuminate\Database\Migrations\Migration;

class CreateBankHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('bank_history');

        Schema::create('bank_history', function($table)
        {
            $table->increments('id');
			$table->integer('bank_id')->unsigned();
			$table->string('currency', 5);
			$table->float('amount');
			$table->float('current_amount');	
        	$table->string('transaction_id', 15);					
        	$table->string('memo', 255)->nullable();
			$table->timestamps();

			$table->foreign('bank_id')->references('id')->on('banks');
			$table->foreign('transaction_id')->references('id')->on('transactions');
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
		Schema::drop('bank_history');
	}

}