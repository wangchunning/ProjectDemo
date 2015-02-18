<?php

use Illuminate\Database\Migrations\Migration;

class UpdateTransactionsAddCurrencyToTable extends Migration {
	/*
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `transactions` CHANGE `currency` `currency_to` varchar(5) NOT NULL DEFAULT ''," . 
					"ADD COLUMN `currency_from` varchar(5) NOT NULL DEFAULT '' AFTER `amount`, ".
					"ADD COLUMN `rate` decimal(18,4) NOT NULL DEFAULT 0 AFTER `currency_to`";
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