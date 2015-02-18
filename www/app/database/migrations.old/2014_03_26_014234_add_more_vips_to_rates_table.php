<?php

use Illuminate\Database\Migrations\Migration;

class AddMoreVipsToRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('rates', function($table) 
		{
			$_sql = "ALTER TABLE `rates` CHANGE `bid` `bid_margin` int NOT NULL DEFAULT 0," . 
						"CHANGE `ask` `ask_margin` int NOT NULL DEFAULT 0, ".
						"CHANGE `vip_bid` `silver_bid_margin` int NOT NULL DEFAULT 0, ".
						"CHANGE `vip_ask` `silver_ask_margin` int NOT NULL DEFAULT 0, ".
						"ADD COLUMN `golden_bid_margin` int NOT NULL DEFAULT 0 AFTER `silver_ask_margin`, ".
						"ADD COLUMN `golden_ask_margin` int NOT NULL DEFAULT 0 AFTER `golden_bid_margin`, ".
						"ADD COLUMN `platinum_bid_margin` int NOT NULL DEFAULT 0 AFTER `golden_ask_margin`, ".
						"ADD COLUMN `platinum_ask_margin` int NOT NULL DEFAULT 0 AFTER `platinum_bid_margin` ";

			DB::statement($_sql);

        });

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
    		$table->dropColumn('golden_bid_margin');
    		$table->dropColumn('golden_ask_margin');
    		$table->dropColumn('platinum_bid_margin');
    		$table->dropColumn('platinum_ask_margin');

			$_sql = "ALTER TABLE `rates` CHANGE `bid_margin` `bid` int NOT NULL DEFAULT 0," . 
						"CHANGE `ask_margin` `ask` int NOT NULL DEFAULT 0, ".
						"CHANGE `silver_bid_margin` `vip_bid` int NOT NULL DEFAULT 0, ".
						"CHANGE `silver_ask_margin` `vip_ask` int NOT NULL DEFAULT 0";

			DB::statement($_sql);

		});
	}

}