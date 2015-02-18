<?php

use Illuminate\Database\Migrations\Migration;

class UpdateBalanceMaxAmountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/* balance_history */
		$_sql = "ALTER TABLE `balance_history`" .
			"CHANGE `pre_amount` `pre_amount` decimal(12, 2) NOT NULL DEFAULT 0,".
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0";
		DB::statement($_sql);

		/* balance_history */
		$_sql = "ALTER TABLE `balances`" .
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0,".
			"CHANGE `frozen_amount` `frozen_amount` decimal(12, 2) NOT NULL DEFAULT 0";
		DB::statement($_sql);

		/* bank_history */
		$_sql = "ALTER TABLE `bank_history`" .
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0,".
			"CHANGE `current_amount` `current_amount` decimal(12, 2) NOT NULL DEFAULT 0";
		DB::statement($_sql);

		/* transaction_bank */
		$_sql = "ALTER TABLE `transaction_bank`" .
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0";
		DB::statement($_sql);				

		/* transfer_history */
		$_sql = "ALTER TABLE `transfer_history`" .
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0,".
			"CHANGE `amount_to_aud` `amount_to_aud` decimal(12, 2) NOT NULL DEFAULT 0";
		DB::statement($_sql);	

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}