<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransferHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('transfer_history');

        Schema::create('transfer_history', function($table)
        {
            $table->increments('id');
			$table->integer('uid')->unsigned();
			$table->integer('recipient_id')->unsigned();
			$table->string('receipt_id', 18);
			$table->string('currency', 5);
			$table->float('amount');
			$table->float('aud_rate');
			$table->float('amount_to_aud');
			$table->timestamps();

			$table->foreign('uid')->references('uid')->on('users');
			$table->foreign('recipient_id')->references('id')->on('recipients');
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
		Schema::drop('transfer_history');
	}

}