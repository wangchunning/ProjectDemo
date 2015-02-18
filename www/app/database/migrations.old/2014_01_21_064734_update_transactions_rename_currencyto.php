<?php

use Illuminate\Database\Migrations\Migration;

class UpdateTransactionsRenameCurrencyto extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "ALTER TABLE `transactions` CHANGE `currency_to` `currency` varchar(5) NOT NULL DEFAULT ''," . 
					"ADD COLUMN `currency_to` varchar(5) NOT NULL DEFAULT '' AFTER `currency_from`";					

		DB::statement($sql);
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