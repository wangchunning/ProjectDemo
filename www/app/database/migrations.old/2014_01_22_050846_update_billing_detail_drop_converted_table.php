<?php

use Illuminate\Database\Migrations\Migration;

class UpdateBillingDetailDropConvertedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sql = "ALTER TABLE `billing_detail` DROP COLUMN `converted_currency_from`," . 
					"DROP COLUMN `converted_currency_to`, ".
					"DROP COLUMN `bank_converted_buy_amount`, ".
					"DROP COLUMN `bank_converted_sell_amount` ";

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