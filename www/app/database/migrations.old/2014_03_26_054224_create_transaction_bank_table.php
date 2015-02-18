<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionBankTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('transaction_bank');

        Schema::create('transaction_bank', function($table)
        {
            $table->increments('id');
			$table->integer('bank_id')->unsigned();
			$table->string('transaction_id', 15);
			$table->string('currency', 5);
			$table->float('amount');
			$table->tinyInteger('status')->unsigned()->default(0);
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
		Schema::drop('transaction_bank');
	}

}