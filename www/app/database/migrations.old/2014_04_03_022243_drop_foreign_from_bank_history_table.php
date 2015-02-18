<?php

use Illuminate\Database\Migrations\Migration;

class DropForeignFromBankHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bank_history', function($table) 
		{
			$table->dropForeign('bank_history_transaction_id_foreign');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bank_history', function($table) {
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
	}

}