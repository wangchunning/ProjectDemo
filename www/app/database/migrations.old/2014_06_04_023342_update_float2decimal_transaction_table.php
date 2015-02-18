<?php

use Illuminate\Database\Migrations\Migration;

class UpdateFloat2decimalTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		/* transaction */
		$_sql = "ALTER TABLE `transactions`" .
			"CHANGE `amount` `amount` decimal(12, 2) NOT NULL DEFAULT 0";
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