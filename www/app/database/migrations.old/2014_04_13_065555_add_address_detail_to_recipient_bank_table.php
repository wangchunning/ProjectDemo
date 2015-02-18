<?php

use Illuminate\Database\Migrations\Migration;

class AddAddressDetailToRecipientBankTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$_sql = "ALTER TABLE `recipient_banks`" .
					"DROP COLUMN `bank_address`, ".
					"ADD COLUMN `unit_number` varchar(60) NOT NULL DEFAULT '' AFTER `branch_name`, ".
					"ADD COLUMN `street_number` varchar(60) NOT NULL DEFAULT '' AFTER `unit_number`, ".
					"ADD COLUMN `street` varchar(128) NOT NULL DEFAULT '' AFTER `street_number`, ".
					"ADD COLUMN `city` varchar(60) NOT NULL DEFAULT '' AFTER `street`, ".
					"ADD COLUMN `state` varchar(60) NOT NULL DEFAULT '' AFTER `city`, ".
					"ADD COLUMN `postcode` varchar(60) NOT NULL DEFAULT '' AFTER `state` ";

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