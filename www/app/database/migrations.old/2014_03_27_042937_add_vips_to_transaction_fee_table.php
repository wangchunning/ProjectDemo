<?php

use Illuminate\Database\Migrations\Migration;

class AddVipsToTransactionFeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			$_sql = "ALTER TABLE `transaction_fee`" .
						"CHANGE `amount` `amount` decimal(10,2) NOT NULL DEFAULT 0,".
						"ADD COLUMN `silver_amount` decimal(10,2) NOT NULL DEFAULT 0 AFTER `amount`, ".
						"ADD COLUMN `golden_amount` decimal(10,2) NOT NULL DEFAULT 0 AFTER `silver_amount`, ".
						"ADD COLUMN `platinum_amount` decimal(10,2) NOT NULL DEFAULT 0 AFTER `golden_amount`";

			DB::statement($_sql);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rates', function($table)
		{
    		$table->dropColumn('silver_amount');
    		$table->dropColumn('golden_amount');
    		$table->dropColumn('platinum_amount');
		});
	}

}